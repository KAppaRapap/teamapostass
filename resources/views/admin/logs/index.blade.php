@extends('layouts.app')

@section('content')
<div class="py-16">
    <div class="max-w-8xl mx-auto">
        <!-- Header -->
        <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center mb-12">
            <div>
                <h1 class="font-orbitron font-bold text-4xl lg:text-5xl mb-4">
                    <span class="text-neon-green">Logs</span> do Sistema
                </h1>
                <p class="text-xl text-gray-300">Monitorização de todas as atividades da plataforma</p>
            </div>
            <a href="{{ route('admin.dashboard') }}" class="btn-outline mt-4 lg:mt-0">
                <i class="fas fa-arrow-left mr-2"></i>Voltar ao Dashboard
            </a>
        </div>

        <!-- Filtros -->
        <div class="content-card p-8 mb-8">
            <form method="GET" class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div>
                    <label class="block text-white font-semibold mb-2">Tipo de Atividade</label>
                    <select name="type" class="form-input w-full">
                        <option value="">Todos os tipos</option>
                        @foreach($activityTypes as $type)
                        <option value="{{ $type }}" {{ request('type') == $type ? 'selected' : '' }}>
                            {{ ucfirst(str_replace('_', ' ', $type)) }}
                        </option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-white font-semibold mb-2">Usuário</label>
                    <input type="number" name="user_id" value="{{ request('user_id') }}" 
                           placeholder="ID do usuário..." 
                           class="form-input w-full">
                </div>
                <div class="flex items-end">
                    <button type="submit" class="btn-primary w-full">
                        <i class="fas fa-filter mr-2"></i>Filtrar
                    </button>
                </div>
            </form>
        </div>

        <!-- Estatísticas Rápidas -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
            <div class="content-card p-6 text-center">
                <div class="text-3xl text-blue-400 mb-2">
                    <i class="fas fa-list"></i>
                </div>
                <h3 class="text-2xl font-bold text-white">{{ $activities->total() }}</h3>
                <p class="text-gray-400">Total de Logs</p>
            </div>
            <div class="content-card p-6 text-center">
                <div class="text-3xl text-green-400 mb-2">
                    <i class="fas fa-user-check"></i>
                </div>
                <h3 class="text-2xl font-bold text-white">{{ $activities->where('type', 'login')->count() }}</h3>
                <p class="text-gray-400">Logins</p>
            </div>
            <div class="content-card p-6 text-center">
                <div class="text-3xl text-yellow-400 mb-2">
                    <i class="fas fa-dice"></i>
                </div>
                <h3 class="text-2xl font-bold text-white">{{ $activities->where('type', 'bet')->count() }}</h3>
                <p class="text-gray-400">Apostas</p>
            </div>
            <div class="content-card p-6 text-center">
                <div class="text-3xl text-red-400 mb-2">
                    <i class="fas fa-shield-alt"></i>
                </div>
                <h3 class="text-2xl font-bold text-white">{{ $activities->where('type', 'admin_action')->count() }}</h3>
                <p class="text-gray-400">Ações Admin</p>
            </div>
        </div>

        <!-- Lista de Atividades -->
        <div class="content-card overflow-hidden">
            <div class="p-6 border-b border-gray-700">
                <h3 class="text-xl font-bold text-white">
                    <i class="fas fa-clock text-neon-green mr-2"></i>Atividades Recentes
                </h3>
            </div>
            
            @if($activities->count() > 0)
            <div class="divide-y divide-gray-700">
                @foreach($activities as $activity)
                <div class="p-6 hover:bg-gray-800 transition-colors">
                    <div class="flex items-start justify-between">
                        <div class="flex items-start gap-4 flex-1">
                            <!-- Ícone do tipo de atividade -->
                            <div class="flex-shrink-0 w-12 h-12 rounded-full flex items-center justify-center
                                {{ $activity->type == 'admin_action' ? 'bg-red-600' : '' }}
                                {{ $activity->type == 'bet' ? 'bg-yellow-600' : '' }}
                                {{ $activity->type == 'win' ? 'bg-green-600' : '' }}
                                {{ $activity->type == 'login' ? 'bg-blue-600' : '' }}
                                {{ !in_array($activity->type, ['admin_action', 'bet', 'win', 'login']) ? 'bg-gray-600' : '' }}">
                                <i class="fas 
                                    {{ $activity->type == 'admin_action' ? 'fa-shield-alt' : '' }}
                                    {{ $activity->type == 'bet' ? 'fa-dice' : '' }}
                                    {{ $activity->type == 'win' ? 'fa-trophy' : '' }}
                                    {{ $activity->type == 'login' ? 'fa-sign-in-alt' : '' }}
                                    {{ !in_array($activity->type, ['admin_action', 'bet', 'win', 'login']) ? 'fa-info-circle' : '' }}
                                    text-white"></i>
                            </div>
                            
                            <!-- Detalhes da atividade -->
                            <div class="flex-1">
                                <div class="flex items-center gap-3 mb-2">
                                    <h4 class="font-semibold text-white">{{ $activity->description }}</h4>
                                    <span class="px-2 py-1 bg-gray-700 text-gray-300 text-xs rounded uppercase">
                                        {{ str_replace('_', ' ', $activity->type) }}
                                    </span>
                                </div>
                                
                                <div class="flex items-center gap-4 text-sm text-gray-400">
                                    <span>
                                        <i class="fas fa-user mr-1"></i>
                                        {{ $activity->user->name ?? 'Sistema' }}
                                    </span>
                                    <span>
                                        <i class="fas fa-clock mr-1"></i>
                                        {{ $activity->created_at->format('d/m/Y H:i:s') }}
                                    </span>
                                    <span>
                                        <i class="fas fa-calendar mr-1"></i>
                                        {{ $activity->created_at->diffForHumans() }}
                                    </span>
                                </div>
                                
                                <!-- Dados adicionais se existirem -->
                                @if($activity->data && is_array($activity->data))
                                <div class="mt-3 p-3 bg-gray-800 rounded-lg">
                                    <details class="cursor-pointer">
                                        <summary class="text-sm text-gray-300 hover:text-white">
                                            <i class="fas fa-info-circle mr-1"></i>Ver detalhes
                                        </summary>
                                        <div class="mt-2 text-xs text-gray-400">
                                            <pre class="whitespace-pre-wrap">{{ json_encode($activity->data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
                                        </div>
                                    </details>
                                </div>
                                @endif
                            </div>
                        </div>
                        
                        <!-- Avatar do usuário -->
                        @if($activity->user)
                        <div class="flex-shrink-0 ml-4">
                            <img src="{{ $activity->user->profile_photo_url }}" 
                                 alt="{{ $activity->user->name }}" 
                                 class="w-10 h-10 rounded-full border-2 border-neon-green">
                        </div>
                        @endif
                    </div>
                </div>
                @endforeach
            </div>
            @else
            <div class="p-12 text-center">
                <div class="text-6xl text-gray-600 mb-4">
                    <i class="fas fa-search"></i>
                </div>
                <p class="text-xl text-gray-400">Nenhuma atividade encontrada</p>
                <p class="text-gray-500 mt-2">Tenta ajustar os filtros ou verificar mais tarde</p>
            </div>
            @endif
        </div>

        <!-- Paginação -->
        @if($activities->hasPages())
        <div class="mt-8">
            {{ $activities->appends(request()->query())->links() }}
        </div>
        @endif

        <!-- Ações Rápidas -->
        <div class="mt-8 flex flex-wrap gap-4 justify-center">
            <a href="{{ route('admin.logs.index') }}" class="btn-outline">
                <i class="fas fa-refresh mr-2"></i>Atualizar
            </a>
            <a href="{{ route('admin.reports.financial') }}" class="btn-outline">
                <i class="fas fa-chart-line mr-2"></i>Ver Relatórios
            </a>
            <button onclick="window.print()" class="btn-primary">
                <i class="fas fa-print mr-2"></i>Imprimir Logs
            </button>
        </div>
    </div>
</div>
@endsection
