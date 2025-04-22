<?php

namespace App\Http\Controllers;

use App\Models\Draw;
use App\Models\Game;
use App\Models\User;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DrawController extends Controller
{
    /**
     * Display a listing of the draws.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $query = Draw::query();
        
        // Filter by game
        if ($request->has('game_id') && $request->game_id) {
            $query->where('game_id', $request->game_id);
        }
        
        // Filter by status
        if ($request->has('status')) {
            if ($request->status === 'upcoming') {
                $query->where('draw_date', '>', now())->where('is_completed', false);
            } elseif ($request->status === 'completed') {
                $query->where('is_completed', true);
            }
        }
        
        $draws = $query->with('game')->orderBy('draw_date', 'desc')->paginate(10);
        $games = Game::all();
        
        return view('draws.index', compact('draws', 'games'));
    }

    /**
     * Show the form for creating a new draw.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->authorize('create', Draw::class);
        
        $games = Game::all();
        return view('draws.create', compact('games'));
    }

    /**
     * Store a newly created draw in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->authorize('create', Draw::class);
        
        $validated = $request->validate([
            'game_id' => 'required|exists:games,id',
            'draw_number' => 'nullable|string|max:255',
            'draw_date' => 'required|date',
            'jackpot' => 'nullable|numeric|min:0',
            'is_completed' => 'boolean',
        ]);
        
        $draw = Draw::create($validated);
        
        // Send notifications to users about the new draw
        $this->notifyUsersAboutNewDraw($draw);
        
        return redirect()->route('draws.index')
            ->with('success', 'Sorteio criado com sucesso.');
    }

    /**
     * Display the specified draw.
     *
     * @param  \App\Models\Draw  $draw
     * @return \Illuminate\Http\Response
     */
    public function show(Draw $draw)
    {
        // Processa automaticamente se já passou a data
        $draw->processIfDue();
        $bettingSlips = $draw->bettingSlips()->with('group')->paginate(10);
        
        return view('draws.show', compact('draw', 'bettingSlips'));
    }

    /**
     * Show the form for editing the specified draw.
     *
     * @param  \App\Models\Draw  $draw
     * @return \Illuminate\Http\Response
     */
    public function edit(Draw $draw)
    {
        $this->authorize('update', $draw);
        
        $games = Game::all();
        return view('draws.edit', compact('draw', 'games'));
    }

    /**
     * Update the specified draw in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Draw  $draw
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Draw $draw)
    {
        $this->authorize('update', $draw);

        $validated = $request->validate([
            'game_id' => 'required|exists:games,id',
            'draw_number' => 'nullable|string|max:255',
            'draw_date' => 'required|date',
            'jackpot_amount' => 'nullable|numeric|min:0',
        ]);

        // Tratar is_completed (checkbox pode não vir no request)
        $validated['is_completed'] = $request->has('is_completed') ? true : false;

        // Tratar winning_numbers
        if ($validated['is_completed']) {
            $request->validate([
                'winning_numbers' => 'required|array',
                'winning_numbers.*' => 'required|integer|min:1',
            ]);
            $validated['winning_numbers'] = $request->winning_numbers;
        } else {
            $validated['winning_numbers'] = null;
        }

        $draw->update($validated);

        // Se agora foi marcado como realizado, notificar utilizadores
        if ($validated['is_completed'] && !$draw->getOriginal('is_completed')) {
            $this->notifyUsersAboutResults($draw);
        }

        return redirect()->route('draws.index')
            ->with('success', 'Sorteio atualizado com sucesso.');
    }

    /**
     * Remove the specified draw from storage.
     *
     * @param  \App\Models\Draw  $draw
     * @return \Illuminate\Http\Response
     */
    public function destroy(Draw $draw)
    {
        $this->authorize('delete', $draw);
        
        $draw->delete();
        
        return redirect()->route('draws.index')
            ->with('success', 'Sorteio excluído com sucesso.');
    }
    
    /**
     * Notify users about a new draw.
     *
     * @param  \App\Models\Draw  $draw
     * @return void
     */
    private function notifyUsersAboutNewDraw(Draw $draw)
    {
        $game = $draw->game;
        
        // Find users who are in groups for this game type
        $userIds = User::whereHas('groups', function($query) use ($game) {
            $query->where('game_id', $game->id);
        })->pluck('id');
        
        foreach ($userIds as $userId) {
            Notification::create([
                'user_id' => $userId,
                'type' => 'new_draw',
                'title' => 'Novo sorteio de ' . $game->name,
                'message' => 'Um novo sorteio de ' . $game->name . ' foi agendado para ' . $draw->draw_date->format('d/m/Y'),
                'data' => [
                    'draw_id' => $draw->id,
                    'game_id' => $game->id,
                ],
            ]);
        }
    }
    
    /**
     * Notify users about draw results.
     *
     * @param  \App\Models\Draw  $draw
     * @return void
     */
    private function notifyUsersAboutResults(Draw $draw)
    {
        $game = $draw->game;
        
        // Find users who have betting slips for this draw
        $userIds = User::whereHas('groups', function($query) use ($draw) {
            $query->whereHas('bettingSlips', function($q) use ($draw) {
                $q->where('draw_id', $draw->id);
            });
        })->pluck('id');
        
        foreach ($userIds as $userId) {
            Notification::create([
                'user_id' => $userId,
                'type' => 'draw_result',
                'title' => 'Resultado do sorteio de ' . $game->name,
                'message' => 'O resultado do sorteio de ' . $game->name . ' já está disponível!',
                'data' => [
                    'draw_id' => $draw->id,
                    'game_id' => $game->id,
                ],
            ]);
        }
    }
    
    /**
     * Display the results page with completed draws.
     *
     * @return \Illuminate\Http\Response
     */
    public function results()
    {
        $games = Game::all();
        $completedDraws = Draw::where('is_completed', true)
            ->with('game')
            ->orderBy('draw_date', 'desc')
            ->paginate(10);
            
        return view('results.index', compact('games', 'completedDraws'));
    }
    
    /**
     * Display the result of a specific draw.
     *
     * @param  \App\Models\Draw  $draw
     * @return \Illuminate\Http\Response
     */
    public function showResult(Draw $draw)
    {
        if (!$draw->is_completed) {
            return redirect()->route('results.index')
                ->with('error', 'Este sorteio ainda não foi realizado.');
        }
        
        $userBettingSlips = [];
        
        // Get user's betting slips for this draw
        if (Auth::check()) {
            // Obter diretamente as apostas do usuário para este sorteio
            $userBettingSlips = Auth::user()->bettingSlips()
                ->where('draw_id', $draw->id)
                ->with(['group', 'draw'])
                ->get();
                
            // Se não encontrar apostas diretas, procurar através dos grupos
            if ($userBettingSlips->isEmpty()) {
                $userBettingSlips = Auth::user()->groups()
                    ->with(['bettingSlips' => function($query) use ($draw) {
                        $query->where('draw_id', $draw->id);
                    }])
                    ->get()
                    ->pluck('bettingSlips')
                    ->flatten();
            }
        }
        
        return view('results.show', compact('draw', 'userBettingSlips'));
    }
}
