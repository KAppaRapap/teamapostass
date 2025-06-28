@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-dark-bg py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="flex items-center justify-between mb-8">
            <div>
                <h1 class="text-3xl font-bold text-white">Detalhes do Grupo</h1>
                <p class="text-gray-400 mt-2">Informações completas sobre o grupo</p>
            </div>
            <div class="flex gap-4">
                <a href="{{ route('admin.groups.index') }}" class="btn-outline">
                    <i class="fas fa-arrow-left mr-2"></i>Voltar
                </a>
            </div>
        </div>

        <!-- Informações Principais -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-8">
            <!-- Card Principal -->
            <div class="lg:col-span-2 content-card p-8">
                <div class="flex items-start justify-between mb-6">
                    <div class="flex-1">
                        <h2 class="text-2xl font-bold text-white mb-2">{{ $group->name }}</h2>
                        <p class="text-gray-400">{{ $group->description ?? 'Sem descrição' }}</p>
                    </div>
                    <div class="flex-shrink-0 ml-4">
                        @if($group->admin)
                        <img src="{{ $group->admin->profile_photo_url }}" alt="{{ $group->admin->name }}" 
                             class="w-16 h-16 rounded-full border-2 border-neon-green" 
                             title="Admin: {{ $group->admin->name }}">
                        @else
                        <div class="w-16 h-16 rounded-full bg-gray-600 flex items-center justify-center">
                            <i class="fas fa-user-slash text-gray-400 text-xl"></i>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Estatísticas -->
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
                    <div class="text-center p-4 bg-gray-800 rounded-lg">
                        <div class="text-2xl font-bold text-neon-green">{{ $stats['total_members'] }}</div>
                        <div class="text-xs text-gray-400">Membros</div>
                    </div>
                    <div class="text-center p-4 bg-gray-800 rounded-lg">
                        <div class="text-2xl font-bold text-blue-400">{{ $stats['days_since_created'] }}</div>
                        <div class="text-xs text-gray-400">Dias</div>
                    </div>
                    <div class="text-center p-4 bg-gray-800 rounded-lg">
                        <div class="text-2xl font-bold text-purple-400">
                            {{ $stats['max_members'] ?: '∞' }}
                        </div>
                        <div class="text-xs text-gray-400">Máx. Membros</div>
                    </div>
                    <div class="text-center p-4 bg-gray-800 rounded-lg">
                        <div class="text-2xl font-bold {{ $stats['is_public'] ? 'text-green-400' : 'text-yellow-400' }}">
                            {{ $stats['is_public'] ? 'Público' : 'Privado' }}
                        </div>
                        <div class="text-xs text-gray-400">Tipo</div>
                    </div>
                </div>

                <!-- Informações Detalhadas -->
                <div class="space-y-4">
                    <div class="flex justify-between items-center py-3 border-b border-gray-700">
                        <span class="text-gray-400">Administrador:</span>
                        <span class="text-white font-semibold">{{ $group->admin->name ?? 'Sem admin' }}</span>
                    </div>
                    <div class="flex justify-between items-center py-3 border-b border-gray-700">
                        <span class="text-gray-400">Jogo Associado:</span>
                        <span class="text-white font-semibold">{{ $group->game->name ?? 'Nenhum' }}</span>
                    </div>
                    <div class="flex justify-between items-center py-3 border-b border-gray-700">
                        <span class="text-gray-400">Cidade:</span>
                        <span class="text-white font-semibold">{{ $group->city ?? 'Não especificada' }}</span>
                    </div>
                    <div class="flex justify-between items-center py-3 border-b border-gray-700">
                        <span class="text-gray-400">Criado em:</span>
                        <span class="text-white font-semibold">{{ $group->created_at->format('d/m/Y H:i') }}</span>
                    </div>
                    <div class="flex justify-between items-center py-3">
                        <span class="text-gray-400">Última atualização:</span>
                        <span class="text-white font-semibold">{{ $stats['last_activity'] }}</span>
                    </div>
                </div>
            </div>

            <!-- Sidebar com Ações -->
            <div class="space-y-6">
                <!-- Ações Rápidas -->
                <div class="content-card p-6">
                    <h3 class="text-lg font-semibold text-white mb-4">Ações</h3>
                    <div class="space-y-3">
                        <a href="{{ route('groups.chat', $group) }}" class="btn-outline w-full text-center">
                            <i class="fas fa-comments mr-2"></i>Ver Chat
                        </a>
                        <form method="POST" action="{{ route('admin.groups.delete', $group) }}" 
                              onsubmit="return confirm('Tem certeza que deseja deletar este grupo? Esta ação não pode ser desfeita.')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn-outline w-full text-red-400 border-red-400 hover:bg-red-500 hover:text-white">
                                <i class="fas fa-trash mr-2"></i>Deletar Grupo
                            </button>
                        </form>
                    </div>
                </div>

                <!-- Status do Grupo -->
                <div class="content-card p-6">
                    <h3 class="text-lg font-semibold text-white mb-4">Status</h3>
                    <div class="space-y-3">
                        <div class="flex items-center justify-between">
                            <span class="text-gray-400">Visibilidade:</span>
                            <span class="px-3 py-1 rounded-full text-xs font-bold {{ $stats['is_public'] ? 'bg-green-600 text-white' : 'bg-yellow-600 text-white' }}">
                                {{ $stats['is_public'] ? 'PÚBLICO' : 'PRIVADO' }}
                            </span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-gray-400">Ocupação:</span>
                            <span class="text-white font-semibold">
                                {{ $stats['total_members'] }}/{{ $stats['max_members'] ?: '∞' }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Lista de Membros -->
        <div class="content-card p-8">
            <h3 class="text-xl font-semibold text-white mb-6">Membros do Grupo</h3>
            
            @if($group->members->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                @foreach($group->members as $member)
                <div class="flex items-center p-4 bg-gray-800 rounded-lg">
                    <img src="{{ $member->profile_photo_url }}" alt="{{ $member->name }}" 
                         class="w-10 h-10 rounded-full mr-3">
                    <div class="flex-1">
                        <div class="font-semibold text-white">{{ $member->name }}</div>
                        <div class="text-sm text-gray-400">{{ $member->email }}</div>
                    </div>
                    @if($member->id === $group->admin_id)
                    <span class="px-2 py-1 bg-neon-green text-dark-bg text-xs font-bold rounded">ADMIN</span>
                    @endif
                </div>
                @endforeach
            </div>
            @else
            <div class="text-center py-8">
                <div class="text-4xl text-gray-600 mb-4">
                    <i class="fas fa-users"></i>
                </div>
                <h4 class="text-lg font-semibold text-white mb-2">Nenhum membro</h4>
                <p class="text-gray-400">Este grupo ainda não possui membros</p>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
