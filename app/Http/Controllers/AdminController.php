<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Game;
use App\Models\Group;
use App\Models\Activity;
use App\Models\BettingSlip;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AdminController extends Controller
{
    /**
     * Dashboard principal do admin
     */
    public function dashboard()
    {
        $stats = [
            'total_users' => User::count(),
            'active_users' => User::where('created_at', '>=', Carbon::now()->subDays(30))->count(),
            'banned_users' => User::where('is_banned', true)->count(),
            'total_groups' => Group::count(),
            'total_bets' => BettingSlip::count(),
            'total_revenue' => BettingSlip::sum('bet_amount'),
            'total_payouts' => BettingSlip::where('has_won', true)->sum('prize_amount'),
            'active_games' => Game::count(),
        ];

        // Estatísticas dos últimos 7 dias
        $weeklyStats = [
            'new_users' => User::where('created_at', '>=', Carbon::now()->subDays(7))->count(),
            'weekly_bets' => BettingSlip::where('created_at', '>=', Carbon::now()->subDays(7))->count(),
            'weekly_revenue' => BettingSlip::where('created_at', '>=', Carbon::now()->subDays(7))->sum('bet_amount'),
        ];

        // Utilizadores mais ativos
        $topUsers = User::withCount(['activities'])
            ->orderBy('activities_count', 'desc')
            ->take(10)
            ->get();

        // Atividades recentes
        $recentActivities = Activity::with('user')
            ->orderBy('created_at', 'desc')
            ->take(20)
            ->get();

        return view('admin.dashboard', compact('stats', 'weeklyStats', 'topUsers', 'recentActivities'));
    }

    /**
     * Gerir utilizadores
     */
    public function users(Request $request)
    {
        $query = User::query();

        // Filtros
        if ($request->has('search') && $request->search) {
            $query->where(function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('email', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->has('status')) {
            switch ($request->status) {
                case 'admin':
                    $query->where('is_admin', true);
                    break;
                case 'banned':
                    $query->where('is_banned', true);
                    break;
                case 'active':
                    $query->where('is_banned', false);
                    break;
            }
        }

        $users = $query->orderBy('created_at', 'desc')->paginate(20);

        return view('admin.users.index', compact('users'));
    }

    /**
     * Editar utilizador
     */
    public function editUser(User $user)
    {
        return view('admin.users.edit', compact('user'));
    }

    /**
     * Nota: A função updateUser foi removida pois dados pessoais (nome/email)
     * devem ser geridos apenas pelo próprio utilizador.
     * Use as funções específicas: toggleBanUser, toggleAdminUser, adjustBalance
     */

    /**
     * Banir/Desbanir utilizador
     */
    public function toggleBanUser(User $user)
    {
        $user->update(['is_banned' => !$user->is_banned]);

        $action = $user->is_banned ? 'banido' : 'desbanido';

        // Log da ação
        Activity::create([
            'user_id' => auth()->id(),
            'type' => 'admin_action',
            'description' => 'Utilizador ' . $user->name . ' foi ' . $action,
            'data' => [
                'target_user_id' => $user->id,
                'action' => $user->is_banned ? 'ban' : 'unban',
            ],
        ]);

        return back()->with('success', 'Utilizador ' . $action . ' com sucesso!');
    }

    /**
     * Promover/Despromover admin
     */
    public function toggleAdminUser(User $user)
    {
        $user->update(['is_admin' => !$user->is_admin]);

        $action = $user->is_admin ? 'promovido a admin' : 'removido de admin';

        // Log da ação
        Activity::create([
            'user_id' => auth()->id(),
            'type' => 'admin_action',
            'description' => 'Utilizador ' . $user->name . ' foi ' . $action,
            'data' => [
                'target_user_id' => $user->id,
                'action' => $user->is_admin ? 'promote' : 'demote',
            ],
        ]);

        return back()->with('success', 'Utilizador ' . $action . ' com sucesso!');
    }

    /**
     * Ajustar saldo do utilizador
     */
    public function adjustBalance(Request $request, User $user)
    {
        $request->validate([
            'amount' => 'required|numeric',
            'type' => 'required|in:add,subtract,set',
            'reason' => 'required|string|max:255',
        ]);

        $oldBalance = $user->virtual_balance;

        switch ($request->type) {
            case 'add':
                $user->virtual_balance += $request->amount;
                break;
            case 'subtract':
                $user->virtual_balance = max(0, $user->virtual_balance - $request->amount);
                break;
            case 'set':
                $user->virtual_balance = max(0, $request->amount);
                break;
        }

        $user->save();

        // Log da ação
        Activity::create([
            'user_id' => auth()->id(),
            'type' => 'admin_action',
            'description' => 'Ajustou saldo de ' . $user->name . ': €' . $oldBalance . ' → €' . $user->virtual_balance,
            'data' => [
                'target_user_id' => $user->id,
                'action' => 'adjust_balance',
                'old_balance' => $oldBalance,
                'new_balance' => $user->virtual_balance,
                'amount' => $request->amount,
                'type' => $request->type,
                'reason' => $request->reason,
            ],
        ]);

        // Criar notificação para o utilizador
        $this->createBalanceAdjustmentNotification($user, $oldBalance, $user->virtual_balance, $request->type, $request->amount, $request->reason);

        return back()->with('success', 'Saldo ajustado com sucesso!');
    }

    /**
     * Criar notificação de ajuste de saldo
     */
    private function createBalanceAdjustmentNotification($user, $oldBalance, $newBalance, $type, $amount, $reason)
    {
        // Determinar o título e mensagem baseado no tipo de ajuste
        switch ($type) {
            case 'add':
                $title = '💰 Saldo Adicionado';
                $message = "Foram adicionados €{$amount} ao seu saldo. Motivo: {$reason}";
                $icon = 'fas fa-plus-circle';
                $color = 'bg-green-600';
                break;
            case 'subtract':
                $title = '💸 Saldo Deduzido';
                $message = "Foram deduzidos €{$amount} do seu saldo. Motivo: {$reason}";
                $icon = 'fas fa-minus-circle';
                $color = 'bg-red-600';
                break;
            case 'set':
                $title = '⚖️ Saldo Definido';
                $message = "Seu saldo foi definido para €{$newBalance}. Motivo: {$reason}";
                $icon = 'fas fa-balance-scale';
                $color = 'bg-blue-600';
                break;
        }

        // Criar a notificação
        \App\Models\Notification::create([
            'user_id' => $user->id,
            'type' => 'balance_adjustment',
            'title' => $title,
            'message' => $message,
            'notifiable_type' => \App\Models\User::class,
            'notifiable_id' => $user->id,
            'data' => json_encode([
                'old_balance' => $oldBalance,
                'new_balance' => $newBalance,
                'amount' => $amount,
                'adjustment_type' => $type,
                'reason' => $reason,
                'admin_id' => auth()->id(),
                'admin_name' => auth()->user()->name,
                'icon' => $icon,
                'color' => $color,
            ]),
            'is_read' => false,
        ]);
    }

    /**
     * Gerenciar grupos
     */
    public function groups(Request $request)
    {
        $query = Group::with(['admin', 'members']);

        if ($request->has('search') && $request->search) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        $groups = $query->orderBy('created_at', 'desc')->paginate(20);

        return view('admin.groups.index', compact('groups'));
    }

    /**
     * Mostrar detalhes do grupo
     */
    public function showGroup(Group $group)
    {
        $group->load(['admin', 'members', 'game']);

        // Estatísticas do grupo
        $stats = [
            'total_members' => $group->members->count(),
            'days_since_created' => round($group->created_at->diffInDays()),
            'last_activity' => $group->updated_at->diffForHumans(),
            'is_public' => $group->is_public,
            'max_members' => $group->max_members,
        ];

        return view('admin.groups.show', compact('group', 'stats'));
    }

    /**
     * Deletar grupo
     */
    public function deleteGroup(Group $group)
    {
        $groupName = $group->name;
        $group->delete();

        // Log da ação
        Activity::create([
            'user_id' => auth()->id(),
            'type' => 'admin_action',
            'description' => 'Deletou o grupo: ' . $groupName,
            'data' => [
                'action' => 'delete_group',
                'group_name' => $groupName,
            ],
        ]);

        return back()->with('success', 'Grupo deletado com sucesso!');
    }

    /**
     * Relatórios financeiros
     */
    public function financialReports(Request $request)
    {
        $period = $request->get('period', '30'); // dias

        $startDate = Carbon::now()->subDays($period);

        $reports = [
            'total_bets' => BettingSlip::where('created_at', '>=', $startDate)->sum('bet_amount'),
            'total_wins' => BettingSlip::where('created_at', '>=', $startDate)->where('has_won', true)->sum('prize_amount'),
            'house_edge' => 0,
            'active_users' => User::whereHas('activities', function($q) use ($startDate) {
                $q->where('created_at', '>=', $startDate);
            })->count(),
            'new_users' => User::where('created_at', '>=', $startDate)->count(),
        ];

        $reports['house_edge'] = $reports['total_bets'] - $reports['total_wins'];
        $reports['house_edge_percentage'] = $reports['total_bets'] > 0 ?
            ($reports['house_edge'] / $reports['total_bets']) * 100 : 0;

        // Dados para gráficos
        $dailyStats = BettingSlip::where('created_at', '>=', $startDate)
            ->selectRaw('DATE(created_at) as date, SUM(bet_amount) as total_bets, COUNT(*) as bet_count')
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        return view('admin.reports.financial', compact('reports', 'dailyStats', 'period'));
    }

    /**
     * Logs de atividades do sistema
     */
    public function systemLogs(Request $request)
    {
        $query = Activity::with('user');

        if ($request->has('type') && $request->type) {
            $query->where('type', $request->type);
        }

        if ($request->has('user_id') && $request->user_id) {
            $query->where('user_id', $request->user_id);
        }

        $activities = $query->orderBy('created_at', 'desc')->paginate(50);

        $activityTypes = Activity::distinct()->pluck('type');

        return view('admin.logs.index', compact('activities', 'activityTypes'));
    }

    /**
     * Configurações do sistema
     */
    public function showConfig()
    {
        $settings = collect([
            'bet_limit' => 1000,
            'min_bet' => 0.01,
            'max_bet' => 10000,
            'house_edge' => 2.5,
            'maintenance_mode' => false,
            'registration_enabled' => true,
            'email_verification_required' => true,
            'default_balance' => 100,
        ]);

        return view('admin.config', compact('settings'));
    }

    /**
     * Atualizar configurações
     */
    public function updateConfig(Request $request)
    {
        // Aqui você pode implementar um sistema de configurações
        // Por agora, vamos apenas simular

        Activity::create([
            'user_id' => auth()->id(),
            'type' => 'admin_action',
            'description' => 'Atualizou configurações do sistema',
            'data' => [
                'action' => 'update_config',
                'settings' => $request->all(),
            ],
        ]);

        return back()->with('success', 'Configurações atualizadas com sucesso!');
    }
}