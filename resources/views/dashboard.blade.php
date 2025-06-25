@extends('layouts.app')

@section('title', 'Dashboard')
@section('description', 'Painel de controlo do TeamApostas')

@section('content')
<div class="py-8 px-6">
    <div class="max-w-7xl mx-auto">
        <!-- Painel de Progresso do Usuário -->
        <div id="user-progress-panel" class="mb-8"></div>
        <!-- Header do Dashboard -->
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8">
            <div>
                <h1 class="font-orbitron font-bold text-3xl lg:text-4xl mb-2">
                    Olá, <span class="text-neon-green">{{ Auth::user()->name }}</span>! 👋
                </h1>
                <p class="text-gray-300">Bem-vindo ao teu painel de controlo</p>
            </div>
            <div class="flex flex-col sm:flex-row gap-3 mt-4 md:mt-0">
                @if(Auth::user()->is_admin)
                    <a href="{{ route('admin.users.index') }}" class="btn-outline">
                        <i class="fas fa-users-cog mr-2"></i> Gerenciar Usuários
                    </a>
                @endif
                <a href="{{ route('groups.create') }}" class="btn-primary">
                    <i class="fas fa-plus mr-2"></i> Novo Grupo
                </a>
            </div>
        </div>

        <!-- Carteira Virtual -->
        @include('dashboard._virtual_wallet')

        <!-- Stats Cards -->
        <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <div class="content-card">
                <div class="flex items-center">
                    <div class="p-3 bg-gradient-to-br from-blue-500 to-blue-600 rounded-lg mr-4">
                        <i class="fas fa-users text-2xl text-white"></i>
                    </div>
                    <div>
                        <p class="text-gray-400 text-sm">Meus Grupos</p>
                        <p class="text-2xl font-bold text-white">{{ Auth::user()->groups()->count() }}</p>
                    </div>
                </div>
            </div>
            
            @php
                $totalGanhoAmount = Auth::user()->activities()->where('type', 'bet_won')->sum('data->amount');
                $totalGanhoPrize = Auth::user()->activities()->where('type', 'bet_won')->sum('data->prize_amount');
                $totalGanho = $totalGanhoAmount + $totalGanhoPrize;
            @endphp
            <div class="content-card">
                <div class="flex items-center">
                    <div class="p-3 bg-gradient-to-br from-neon-green to-green-600 rounded-lg mr-4">
                        <i class="fas fa-euro-sign text-2xl text-white"></i>
                    </div>
                    <div>
                        <p class="text-gray-400 text-sm">Total Ganho</p>
                        <p class="text-2xl font-bold text-neon-green">€{{ number_format($totalGanho, 2) }}</p>
                    </div>
                </div>
            </div>
            
            <div class="content-card">
                <div class="flex items-center">
                    <div class="p-3 bg-gradient-to-br from-neon-pink to-pink-600 rounded-lg mr-4">
                        <i class="fas fa-gamepad text-2xl text-white"></i>
                    </div>
                    <div>
                        <p class="text-gray-400 text-sm">Jogos Jogados</p>
                        <p class="text-2xl font-bold text-white">{{ Auth::user()->bettingSlips()->count() }}</p>
                    </div>
                </div>
            </div>
            
            <div class="content-card">
                <div class="flex items-center">
                    <div class="p-3 bg-gradient-to-br from-purple-500 to-purple-600 rounded-lg mr-4">
                        <i class="fas fa-trophy text-2xl text-white"></i>
                    </div>
                    <div>
                        <p class="text-gray-400 text-sm">Vitórias</p>
                        <p class="text-2xl font-bold text-white">{{ Auth::user()->bettingSlips()->where('has_won', true)->count() }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Meus Grupos -->
        <div class="content-card">
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6">
                <h2 class="font-orbitron font-bold text-2xl mb-4 md:mb-0">
                    Meus <span class="text-neon-green">Grupos</span>
                </h2>
                <a href="{{ route('groups.index') }}" class="btn-outline">
                    <i class="fas fa-eye mr-2"></i> Ver Todos
                </a>
            </div>
            
            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
                @forelse(Auth::user()->groups as $group)
                <div class="bg-dark-bg border border-dark-border rounded-lg p-6 hover:border-neon-green transition-colors duration-300">
                    <div class="flex justify-between items-start mb-4">
                        <div class="flex-1">
                            <h3 class="font-semibold text-lg text-white mb-1">{{ $group->name }}</h3>
                            <p class="text-sm text-gray-400">
                                Admin: <span class="text-neon-green">{{ $group->admin->name ?? 'N/A' }}</span>
                            </p>
                        </div>
                        <span class="px-3 py-1 bg-neon-green text-dark-bg text-xs font-semibold rounded-full">
                            {{ $group->game ? $group->game->name : 'Sem jogo' }}
                        </span>
                    </div>
                    
                    <div class="grid grid-cols-2 gap-4 mb-4">
                        <div>
                            <p class="text-xs text-gray-400">Membros</p>
                            <p class="text-sm font-semibold text-white">
                                {{ $group->members()->count() }}/{{ $group->max_members ?? '∞' }}
                            </p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-400">Localização</p>
                            <p class="text-sm font-semibold text-white">{{ $group->location ?? 'N/A' }}</p>
                        </div>
                    </div>
                    
                    <div class="flex items-center justify-between">
                        <span class="px-2 py-1 bg-blue-500 text-white text-xs rounded">
                            Público
                        </span>
                        <a href="{{ route('groups.chat', $group) }}" class="btn-primary text-sm px-4 py-2">
                            <i class="fas fa-comments mr-1"></i> Chat
                        </a>
                    </div>
                </div>
                @empty
                <div class="col-span-full">
                    <div class="text-center py-12">
                        <div class="w-16 h-16 bg-gradient-to-br from-neon-green to-green-600 rounded-full flex items-center justify-center mx-auto mb-4">
                            <i class="fas fa-users text-2xl text-white"></i>
                        </div>
                        <h3 class="text-xl font-semibold text-white mb-2">Ainda não tens grupos</h3>
                        <p class="text-gray-400 mb-6">Junta-te a um grupo ou cria um novo para começar a jogar em equipa!</p>
                        <a href="{{ route('groups.index') }}" class="btn-primary">
                            <i class="fas fa-plus mr-2"></i> Explorar Grupos
                        </a>
                    </div>
                </div>
                @endforelse
            </div>
        </div>

        <!-- Atividade Recente -->
        <div class="content-card mt-8">
            <h2 class="font-orbitron font-bold text-2xl mb-6">
                Atividade <span class="text-neon-green">Recente</span>
            </h2>
            
            <div class="space-y-4">
                @forelse(Auth::user()->bettingSlips()->latest()->take(5)->get() as $slip)
                <div class="flex items-center justify-between p-4 bg-dark-bg rounded-lg">
                    <div class="flex items-center">
                        <div class="w-10 h-10 bg-gradient-to-br from-neon-green to-green-600 rounded-full flex items-center justify-center mr-4">
                            <i class="fas fa-gamepad text-white"></i>
                        </div>
                        <div>
                            <p class="font-semibold text-white">{{ $slip->game->name ?? 'Jogo' }}</p>
                            <p class="text-sm text-gray-400">{{ $slip->created_at->diffForHumans() }}</p>
                        </div>
                    </div>
                    <div class="text-right">
                        <span class="px-2 py-1 text-xs rounded {{ $slip->has_won ? 'bg-green-500 text-white' : 'bg-red-500 text-white' }}">
                            {{ $slip->has_won ? 'Ganhou' : 'Perdeu' }}
                        </span>
                    </div>
                </div>
                @empty
                <div class="text-center py-8">
                    <div class="w-16 h-16 bg-gradient-to-br from-gray-500 to-gray-600 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-history text-2xl text-white"></i>
                    </div>
                    <p class="text-gray-400">Ainda não tens atividade recente</p>
                </div>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection
