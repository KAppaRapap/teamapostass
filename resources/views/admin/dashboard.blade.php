@extends('layouts.app')

@section('content')
<div class="py-16">
    <div class="max-w-8xl mx-auto">
        <!-- Header -->
        <div class="text-center mb-12">
            <h1 class="font-orbitron font-bold text-5xl lg:text-6xl mb-6">
                <span class="text-neon-green">Painel</span> de Administração
            </h1>
            <p class="text-xl text-gray-300 max-w-3xl mx-auto leading-relaxed">
                Controlo total sobre a plataforma TeamApostas
            </p>
        </div>

        <!-- Estatísticas Principais -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8 mb-12">
            <!-- Total de Utilizadores -->
            <div class="content-card p-8 text-center">
                <div class="text-4xl text-neon-green mb-4">
                    <i class="fas fa-users"></i>
                </div>
                <h3 class="text-3xl font-bold text-white mb-2">{{ number_format($stats['total_users']) }}</h3>
                <p class="text-gray-400">Total de Utilizadores</p>
                <div class="mt-4 text-sm">
                    <span class="text-green-400">+{{ $weeklyStats['new_users'] }}</span>
                    <span class="text-gray-500">esta semana</span>
                </div>
            </div>

            <!-- Utilizadores Ativos -->
            <div class="content-card p-8 text-center">
                <div class="text-4xl text-blue-400 mb-4">
                    <i class="fas fa-user-check"></i>
                </div>
                <h3 class="text-3xl font-bold text-white mb-2">{{ number_format($stats['active_users']) }}</h3>
                <p class="text-gray-400">Utilizadores Ativos (30d)</p>
                <div class="mt-4 text-sm">
                    <span class="text-gray-500">{{ round(($stats['active_users'] / max($stats['total_users'], 1)) * 100, 1) }}% do total</span>
                </div>
            </div>

            <!-- Total de Apostas -->
            <div class="content-card p-8 text-center">
                <div class="text-4xl text-yellow-400 mb-4">
                    <i class="fas fa-dice"></i>
                </div>
                <h3 class="text-3xl font-bold text-white mb-2">{{ number_format($stats['total_bets']) }}</h3>
                <p class="text-gray-400">Total de Apostas</p>
                <div class="mt-4 text-sm">
                    <span class="text-green-400">+{{ $weeklyStats['weekly_bets'] }}</span>
                    <span class="text-gray-500">esta semana</span>
                </div>
            </div>

            <!-- Receita Total -->
            <div class="content-card p-8 text-center">
                <div class="text-4xl text-green-400 mb-4">
                    <i class="fas fa-euro-sign"></i>
                </div>
                <h3 class="text-3xl font-bold text-white mb-2">€{{ number_format($stats['total_revenue'], 2) }}</h3>
                <p class="text-gray-400">Receita Total</p>
                <div class="mt-4 text-sm">
                    <span class="text-green-400">+€{{ number_format($weeklyStats['weekly_revenue'], 2) }}</span>
                    <span class="text-gray-500">esta semana</span>
                </div>
            </div>
        </div>

        <!-- Estatísticas Secundárias -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-12">
            <!-- Utilizadores Banidos -->
            <div class="content-card p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <h4 class="text-lg font-semibold text-white mb-2">Utilizadores Banidos</h4>
                        <p class="text-3xl font-bold text-red-400">{{ $stats['banned_users'] }}</p>
                    </div>
                    <div class="text-3xl text-red-400">
                        <i class="fas fa-user-slash"></i>
                    </div>
                </div>
            </div>

            <!-- Total de Grupos -->
            <div class="content-card p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <h4 class="text-lg font-semibold text-white mb-2">Total de Grupos</h4>
                        <p class="text-3xl font-bold text-purple-400">{{ $stats['total_groups'] }}</p>
                    </div>
                    <div class="text-3xl text-purple-400">
                        <i class="fas fa-users"></i>
                    </div>
                </div>
            </div>

            <!-- Pagamentos -->
            <div class="content-card p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <h4 class="text-lg font-semibold text-white mb-2">Total Pago</h4>
                        <p class="text-3xl font-bold text-orange-400">€{{ number_format($stats['total_payouts'], 2) }}</p>
                    </div>
                    <div class="text-3xl text-orange-400">
                        <i class="fas fa-hand-holding-usd"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Ações Rápidas -->
        <div class="content-card p-8 mb-12">
            <h3 class="text-2xl font-bold text-white mb-6">
                <i class="fas fa-bolt text-neon-green mr-3"></i>Ações Rápidas
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <a href="{{ route('admin.users.index') }}" class="btn-primary text-center py-4">
                    <i class="fas fa-users mr-2"></i>Gerir Utilizadores
                </a>
                <a href="{{ route('admin.groups.index') }}" class="btn-outline text-center py-4">
                    <i class="fas fa-layer-group mr-2"></i>Gerir Grupos
                </a>
                <a href="{{ route('admin.reports.financial') }}" class="btn-outline text-center py-4">
                    <i class="fas fa-chart-line mr-2"></i>Relatórios
                </a>
                <a href="{{ route('admin.logs.index') }}" class="btn-outline text-center py-4">
                    <i class="fas fa-list-alt mr-2"></i>Logs do Sistema
                </a>
            </div>
        </div>

        <!-- Utilizadores Mais Ativos e Atividades Recentes -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <!-- Utilizadores Mais Ativos -->
            <div class="content-card p-8">
                <h3 class="text-2xl font-bold text-white mb-6">
                    <i class="fas fa-star text-yellow-400 mr-3"></i>Utilizadores Mais Ativos
                </h3>
                <div class="space-y-4">
                    @foreach($topUsers as $user)
                    <div class="flex items-center justify-between p-4 bg-gray-800 rounded-lg">
                        <div class="flex items-center gap-3">
                            <img src="{{ $user->profile_photo_url }}" alt="{{ $user->name }}" 
                                 class="w-10 h-10 rounded-full border-2 border-neon-green">
                            <div>
                                <p class="font-semibold text-white">{{ $user->name }}</p>
                                <p class="text-sm text-gray-400">{{ $user->activities_count }} atividades</p>
                            </div>
                        </div>
                        @if($user->is_admin)
                            <span class="px-2 py-1 bg-neon-green text-dark-bg text-xs font-bold rounded">ADMIN</span>
                        @endif
                    </div>
                    @endforeach
                </div>
            </div>

            <!-- Atividades Recentes -->
            <div class="content-card p-8">
                <h3 class="text-2xl font-bold text-white mb-6">
                    <i class="fas fa-clock text-blue-400 mr-3"></i>Atividades Recentes
                </h3>
                <div class="space-y-3 max-h-96 overflow-y-auto">
                    @foreach($recentActivities as $activity)
                    <div class="p-3 bg-gray-800 rounded-lg">
                        <div class="flex items-start justify-between">
                            <div class="flex-1">
                                <p class="text-white text-sm">{{ $activity->description }}</p>
                                <div class="flex items-center gap-2 mt-1">
                                    <span class="text-xs text-gray-400">{{ $activity->user->name ?? 'Sistema' }}</span>
                                    <span class="text-xs text-gray-500">•</span>
                                    <span class="text-xs text-gray-500">{{ $activity->created_at->diffForHumans() }}</span>
                                </div>
                            </div>
                            <span class="px-2 py-1 bg-gray-700 text-gray-300 text-xs rounded">
                                {{ ucfirst($activity->type) }}
                            </span>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
