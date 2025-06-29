@extends('layouts.app')

@section('content')
<div class="py-16">
    <div class="max-w-8xl mx-auto">
        <!-- Header -->
        <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center mb-12">
            <div>
                <h1 class="font-orbitron font-bold text-4xl lg:text-5xl mb-4">
                    <span class="text-neon-green">Gerir</span> Grupos
                </h1>
                <p class="text-xl text-gray-300">Controlo sobre todos os grupos da plataforma</p>
            </div>
            <a href="{{ route('admin.dashboard') }}" class="btn-outline mt-4 lg:mt-0">
                <i class="fas fa-arrow-left mr-2"></i>Voltar ao Dashboard
            </a>
        </div>

        @if(session('success'))
        <div class="bg-green-600 text-white rounded-lg px-6 py-4 mb-8 flex items-center gap-3">
            <i class="fas fa-check-circle text-xl"></i>
            <span class="text-lg">{{ session('success') }}</span>
        </div>
        @endif

        <!-- Filtros e Pesquisa -->
        <div class="content-card p-8 mb-8">
            <form method="GET" class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div>
                    <label class="block text-white font-semibold mb-2">Pesquisar Grupo</label>
                    <input type="text" name="search" value="{{ request('search') }}" 
                           placeholder="Nome do grupo..." 
                           class="form-input w-full">
                </div>
                <div class="flex items-end">
                    <button type="submit" class="btn-primary w-full">
                        <i class="fas fa-search mr-2"></i>Filtrar
                    </button>
                </div>
                <div class="flex items-end">
                    <a href="{{ route('admin.groups.index') }}" class="btn-outline w-full text-center">
                        <i class="fas fa-times mr-2"></i>Limpar
                    </a>
                </div>
            </form>
        </div>

        <!-- Estatísticas Rápidas -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
            <div class="content-card p-6 text-center">
                <div class="text-3xl text-purple-400 mb-2">
                    <i class="fas fa-layer-group"></i>
                </div>
                <h3 class="text-2xl font-bold text-white">{{ $groups->total() }}</h3>
                <p class="text-gray-400">Total de Grupos</p>
            </div>
            <div class="content-card p-6 text-center">
                <div class="text-3xl text-blue-400 mb-2">
                    <i class="fas fa-users"></i>
                </div>
                <h3 class="text-2xl font-bold text-white">{{ $groups->sum(function($group) { return $group->members->count(); }) }}</h3>
                <p class="text-gray-400">Total de Membros</p>
            </div>
            <div class="content-card p-6 text-center">
                <div class="text-3xl text-green-400 mb-2">
                    <i class="fas fa-chart-line"></i>
                </div>
                <h3 class="text-2xl font-bold text-white">{{ $groups->where('members_count', '>', 0)->count() }}</h3>
                <p class="text-gray-400">Grupos Ativos</p>
            </div>
            <div class="content-card p-6 text-center">
                <div class="text-3xl text-yellow-400 mb-2">
                    <i class="fas fa-crown"></i>
                </div>
                <h3 class="text-2xl font-bold text-white">{{ $groups->whereNotNull('admin_id')->count() }}</h3>
                <p class="text-gray-400">Com Admin</p>
            </div>
        </div>

        <!-- Lista de Grupos -->
        @if($groups->count() > 0)
        <div class="grid grid-cols-1 lg:grid-cols-2 xl:grid-cols-3 gap-8">
            @foreach($groups as $group)
            <div class="content-card p-8">
                <!-- Header do Grupo -->
                <div class="flex items-start justify-between mb-6">
                    <div class="flex-1">
                        <h3 class="text-xl font-bold text-white mb-2">{{ $group->name }}</h3>
                        <p class="text-gray-400 text-sm">{{ Str::limit($group->description ?? 'Sem descrição', 100) }}</p>
                    </div>
                    <div class="flex-shrink-0 ml-4">
                        @if($group->admin)
                        <img src="{{ $group->admin->profile_photo_url }}" alt="{{ $group->admin->name }}" 
                             class="w-12 h-12 rounded-full border-2 border-neon-green" 
                             title="Admin: {{ $group->admin->name }}">
                        @else
                        <div class="w-12 h-12 rounded-full bg-gray-600 flex items-center justify-center">
                            <i class="fas fa-user-slash text-gray-400"></i>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Estatísticas do Grupo -->
                <div class="grid grid-cols-2 gap-4 mb-6">
                    <div class="text-center p-3 bg-gray-800 rounded-lg">
                        <div class="text-2xl font-bold text-neon-green">{{ $group->members->count() }}</div>
                        <div class="text-xs text-gray-400">Membros</div>
                    </div>
                    <div class="text-center p-3 bg-gray-800 rounded-lg">
                        <div class="text-2xl font-bold text-blue-400">{{ round($group->created_at->diffInDays()) }}</div>
                        <div class="text-xs text-gray-400">Dias</div>
                    </div>
                </div>

                <!-- Informações Adicionais -->
                <div class="space-y-3 mb-6">
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-400">Admin:</span>
                        <span class="text-white">{{ $group->admin->name ?? 'Sem admin' }}</span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-400">Criado:</span>
                        <span class="text-white">{{ $group->created_at->format('d/m/Y') }}</span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-400">Atualizado:</span>
                        <span class="text-white">{{ $group->updated_at->diffForHumans() }}</span>
                    </div>
                </div>

                <!-- Membros Recentes -->
                @if($group->members->count() > 0)
                <div class="mb-6">
                    <h4 class="text-sm font-semibold text-gray-300 mb-3">Membros Recentes</h4>
                    <div class="flex -space-x-2">
                        @foreach($group->members->take(5) as $member)
                        <img src="{{ $member->profile_photo_url }}" alt="{{ $member->name }}" 
                             class="w-8 h-8 rounded-full border-2 border-gray-800" 
                             title="{{ $member->name }}">
                        @endforeach
                        @if($group->members->count() > 5)
                        <div class="w-8 h-8 rounded-full bg-gray-600 border-2 border-gray-800 flex items-center justify-center">
                            <span class="text-xs text-white">+{{ $group->members->count() - 5 }}</span>
                        </div>
                        @endif
                    </div>
                </div>
                @endif

                <!-- Ações -->
                <div class="space-y-3">
                    <a href="{{ route('admin.groups.show', $group) }}" class="btn-outline w-full text-center text-sm py-2">
                        <i class="fas fa-eye mr-2"></i>Ver Detalhes
                    </a>
                    <form method="POST" action="{{ route('admin.groups.delete', $group) }}" 
                          onsubmit="return confirm('Tem certeza que deseja deletar este grupo? Esta ação não pode ser desfeita.')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn-outline w-full text-red-400 border-red-400 hover:bg-red-500 hover:text-white text-sm py-2">
                            <i class="fas fa-trash mr-2"></i>Deletar Grupo
                        </button>
                    </form>
                </div>
            </div>
            @endforeach
        </div>
        @else
        <div class="content-card p-12 text-center">
            <div class="text-6xl text-gray-600 mb-4">
                <i class="fas fa-layer-group"></i>
            </div>
            <h3 class="text-2xl font-bold text-white mb-2">Nenhum grupo encontrado</h3>
            <p class="text-gray-400">Não há grupos que correspondam aos filtros aplicados</p>
        </div>
        @endif

        <!-- Paginação -->
        @if($groups->hasPages())
        <div class="mt-8">
            {{ $groups->appends(request()->query())->links() }}
        </div>
        @endif

        <!-- Ações Rápidas -->
        <div class="mt-8 flex flex-wrap gap-4 justify-center">
            <a href="{{ route('admin.groups.index') }}" class="btn-outline">
                <i class="fas fa-refresh mr-2"></i>Atualizar
            </a>
            <a href="{{ route('admin.users.index') }}" class="btn-outline">
                <i class="fas fa-users mr-2"></i>Ver Utilizadores
            </a>
            <a href="{{ route('admin.dashboard') }}" class="btn-primary">
                <i class="fas fa-tachometer-alt mr-2"></i>Dashboard
            </a>
        </div>
    </div>
</div>
@endsection
