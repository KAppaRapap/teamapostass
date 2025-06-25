@extends('layouts.app')

@section('title', 'Grupos')
@section('description', 'Junta-te a grupos de apostadores e joga em equipa')

@section('content')
<div class="py-8 px-6">
    <div class="max-w-7xl mx-auto">
        <!-- Header -->
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8">
            <div>
                <h1 class="font-orbitron font-bold text-3xl lg:text-4xl mb-2">
                    <span class="text-neon-green">Grupos</span> de Apostadores
                </h1>
                <p class="text-gray-300">Junta-te a grupos e joga em equipa</p>
            </div>
            <a href="{{ route('groups.create') }}" class="btn-primary mt-4 md:mt-0">
                <i class="fas fa-plus mr-2"></i> Novo Grupo
            </a>
        </div>

        <!-- Flash Messages -->
        @if(session('success'))
        <div class="bg-green-500 bg-opacity-20 border border-green-500 text-green-300 px-4 py-3 rounded-lg mb-6 flash-message">
            <i class="fas fa-check-circle mr-2"></i>{{ session('success') }}
        </div>
        @endif

        @if(session('error'))
        <div class="bg-red-500 bg-opacity-20 border border-red-500 text-red-300 px-4 py-3 rounded-lg mb-6 flash-message">
            <i class="fas fa-exclamation-circle mr-2"></i>{{ session('error') }}
        </div>
        @endif

        <!-- Lista de Grupos -->
        <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($groups as $group)
            <div class="bg-dark-bg border border-dark-border rounded-lg p-6 hover:border-neon-green transition-all duration-300 group">
                <div class="flex justify-between items-start mb-4">
                    <h3 class="font-semibold text-lg text-white group-hover:text-neon-green transition-colors">
                        {{ $group->name }}
                    </h3>
                    <span class="px-3 py-1 bg-neon-green text-dark-bg text-xs font-semibold rounded-full">
                        {{ $group->game ? $group->game->name : 'Sem jogo' }}
                    </span>
                </div>
                
                <p class="text-gray-300 text-sm mb-4">{{ Str::limit($group->description, 100) }}</p>
                
                <div class="grid grid-cols-3 gap-4 mb-4">
                    <div>
                        <p class="text-xs text-gray-400">Administrador</p>
                        <p class="text-sm font-semibold text-white">{{ $group->admin->name }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-400">Membros</p>
                        <p class="text-sm font-semibold text-white">
                            {{ $group->members->count() }}/{{ $group->max_members ?: '∞' }}
                        </p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-400">Localização</p>
                        <p class="text-sm font-semibold text-white">{{ $group->city ?: 'N/A' }}</p>
                    </div>
                </div>
                
                <div class="flex items-center justify-between">
                    <span class="px-2 py-1 text-xs rounded {{ $group->is_public ? 'bg-green-500 text-white' : 'bg-yellow-500 text-white' }}">
                        {{ $group->is_public ? 'Público' : 'Privado' }}
                    </span>
                    <div class="flex gap-2">
                        <a href="{{ route('groups.chat', $group) }}" class="btn-outline text-sm px-3 py-1">
                            <i class="fas fa-comments mr-1"></i> Chat
                        </a>
                        @if(!$group->members->contains(auth()->user()))
                        <form action="{{ route('groups.join', $group) }}" method="POST" class="inline">
                            @csrf
                            <button type="submit" class="btn-primary text-sm px-3 py-1">
                                <i class="fas fa-sign-in-alt mr-1"></i> Entrar
                            </button>
                        </form>
                        @endif
                    </div>
                </div>
            </div>
            @empty
            <div class="col-span-full">
                <div class="text-center py-12">
                    <div class="w-16 h-16 bg-gradient-to-br from-neon-green to-green-600 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-users text-2xl text-white"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-white mb-2">Nenhum grupo encontrado</h3>
                    <p class="text-gray-400 mb-6">Não foram encontrados grupos com os filtros selecionados.</p>
                    <a href="{{ route('groups.create') }}" class="btn-primary">
                        <i class="fas fa-plus mr-2"></i> Criar Novo Grupo
                    </a>
                </div>
            </div>
            @endforelse
        </div>

        <!-- Paginação -->
        @if($groups->hasPages())
        <div class="mt-8 flex justify-center">
            {{ $groups->withQueryString()->links('groups.pagination') }}
        </div>
        @endif
    </div>
</div>
@endsection
