<?php

namespace App\Http\Controllers;

use App\Models\Group;
use App\Models\Game;
use App\Models\User;
use App\Models\CustomNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GroupController extends Controller
{
    /**
     * Display a listing of the groups.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $query = Group::query();
        
        // Filter by game type
        if ($request->has('game_id') && $request->game_id) {
            $query->where('game_id', $request->game_id);
        }
        
        // Only show public groups or groups the user is a member of
        $query->where(function($q) {
            $q->where('is_public', true)
              ->orWhere('admin_id', Auth::id())
              ->orWhereHas('members', function($q) {
                  $q->where('users.id', Auth::id());
              });
        });
        
        $groups = $query->with('game', 'admin')->paginate(10);
        $games = Game::all();
        
        return view('groups.index', compact('groups', 'games'));
    }

    /**
     * Show the form for creating a new group.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $games = Game::all();
        return view('groups.create', compact('games'));
    }

    /**
     * Store a newly created group in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'game_id' => 'nullable|exists:games,id',
            'is_public' => 'boolean',
            'max_members' => 'integer|min:0',
        ]);
        
        $validated['admin_id'] = Auth::id();
        
        $group = Group::create($validated);
        
        // Add the creator as a member
        $group->members()->attach(Auth::id(), ['status' => 'active']);
        
        return redirect()->route('groups.index')
            ->with('success', 'Grupo criado com sucesso.');
    }

    /**
     * Display the specified group.
     *
     * @param  \App\Models\Group  $group
     * @return \Illuminate\Http\Response
     */
    public function show(Group $group)
    {
        $members = $group->members()->paginate(10);
        $bettingSlips = $group->bettingSlips()->with('draw')->latest()->paginate(5);
        $userIsMember = $group->members()->where('users.id', Auth::id())->exists();
        $userIsAdmin = $group->admin_id === Auth::id();
        
        return view('groups.show', compact('group', 'members', 'bettingSlips', 'userIsMember', 'userIsAdmin'));
    }

    /**
     * Show the form for editing the specified group.
     *
     * @param  \App\Models\Group  $group
     * @return \Illuminate\Http\Response
     */
    public function edit(Group $group)
    {
        $this->authorize('update', $group);
        
        $games = Game::all();
        return view('groups.edit', compact('group', 'games'));
    }

    /**
     * Update the specified group in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Group  $group
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Group $group)
    {
        $this->authorize('update', $group);
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'game_id' => 'nullable|exists:games,id',
            'city' => 'nullable|string|max:255',
            'region' => 'nullable|string|max:255',
            'is_public' => 'boolean',
            'max_members' => 'integer|min:0',
        ]);
        
        $group->update($validated);
        
        return redirect()->route('groups.chat', $group)
            ->with('success', 'Grupo atualizado com sucesso.');
    }

    /**
     * Remove the specified group from storage.
     *
     * @param  \App\Models\Group  $group
     * @return \Illuminate\Http\Response
     */
    public function destroy(Group $group)
    {
        $this->authorize('delete', $group);
        
        $group->delete();
        
        return redirect()->route('groups.index')
            ->with('success', 'Grupo excluído com sucesso.');
    }
    
    /**
     * Join a group.
     *
     * @param  \App\Models\Group  $group
     * @return \Illuminate\Http\Response
     */
    public function join(Group $group)
    {
        // Check if user is already a member
        if ($group->members()->where('users.id', Auth::id())->exists()) {
            return redirect()->route('groups.chat', $group)
                ->with('info', 'Você já é membro deste grupo.');
        }
        
        // Check if group has reached member limit
        if ($group->hasReachedMemberLimit()) {
            return redirect()->route('groups.chat', $group)
                ->with('error', 'Este grupo atingiu o limite máximo de membros.');
        }
        
        // Add user to group
        $group->members()->attach(Auth::id(), ['status' => 'active']);
        
        // Notify group admin
        CustomNotification::create([
            'user_id' => $group->admin_id,
            'type' => 'group_join',
            'title' => 'Novo membro no grupo',
            'message' => Auth::user()->name . ' entrou no grupo ' . $group->name,
            'data' => [
                'group_id' => $group->id,
                'user_id' => Auth::id(),
            ],
        ]);
        
        return redirect()->route('groups.chat', $group)
            ->with('success', 'Você entrou no grupo com sucesso.');
    }
    
    /**
     * Leave a group.
     *
     * @param  \App\Models\Group  $group
     * @return \Illuminate\Http\Response
     */
    public function leave(Group $group)
    {
        // Check if user is the admin
        if ($group->admin_id === Auth::id()) {
            return redirect()->route('groups.chat', $group)
                ->with('error', 'O administrador não pode sair do grupo. Transfira a administração primeiro ou exclua o grupo.');
        }
        
        // Check if user is a member
        if (!$group->members()->where('users.id', Auth::id())->exists()) {
            return redirect()->route('groups.chat', $group)
                ->with('error', 'Você não é membro deste grupo.');
        }
        
        // Remove user from group
        $group->members()->detach(Auth::id());
        
        // Notify group admin
        CustomNotification::create([
            'user_id' => $group->admin_id,
            'type' => 'group_leave',
            'title' => 'Membro saiu do grupo',
            'message' => Auth::user()->name . ' saiu do grupo ' . $group->name,
            'data' => [
                'group_id' => $group->id,
                'user_id' => Auth::id(),
            ],
        ]);
        
        return redirect()->route('groups.index')
            ->with('success', 'Você saiu do grupo com sucesso.');
    }
    
    /**
     * Ban a user from the group.
     *
     * @param  \App\Models\Group  $group
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function banUser(Group $group, User $user)
    {
        $this->authorize('update', $group);
        
        // Update user status to banned
        $group->members()->updateExistingPivot($user->id, ['status' => 'banned']);
        
        // Notify the banned user
        CustomNotification::create([
            'user_id' => $user->id,
            'type' => 'group_ban',
            'title' => 'Banido do grupo',
            'message' => 'Você foi banido do grupo ' . $group->name,
            'data' => [
                'group_id' => $group->id,
            ],
        ]);
        
        return redirect()->route('groups.chat', $group)
            ->with('success', 'Utilizador banido com sucesso.');
    }
    
    /**
     * Unban a user from the group.
     *
     * @param  \App\Models\Group  $group
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function unbanUser(Group $group, User $user)
    {
        $this->authorize('update', $group);
        
        // Update user status to active
        $group->members()->updateExistingPivot($user->id, ['status' => 'active']);
        
        // Notify the unbanned user
        CustomNotification::create([
            'user_id' => $user->id,
            'type' => 'group_unban',
            'title' => 'Desbanido do grupo',
            'message' => 'Você foi desbanido do grupo ' . $group->name,
            'data' => [
                'group_id' => $group->id,
            ],
        ]);
        
        return redirect()->route('groups.chat', $group)
            ->with('success', 'Utilizador desbanido com sucesso.');
    }

    /**
     * Verifica se o utilizador autenticado é membro do grupo (API)
     */
    public function isMember($groupId)
    {
        $group = Group::findOrFail($groupId);
        $isMember = $group->members()->where('users.id', Auth::id())->exists();
        return response()->json(['isMember' => $isMember]);
    }
}
