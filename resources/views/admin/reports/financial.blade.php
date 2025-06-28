@extends('layouts.app')

@section('content')
<div class="py-16">
    <div class="max-w-8xl mx-auto">
        <!-- Header -->
        <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center mb-12">
            <div>
                <h1 class="font-orbitron font-bold text-4xl lg:text-5xl mb-4">
                    <span class="text-neon-green">Relatórios</span> Financeiros
                </h1>
                <p class="text-xl text-gray-300">Análise detalhada das finanças da plataforma</p>
            </div>
            <a href="{{ route('admin.dashboard') }}" class="btn-outline mt-4 lg:mt-0">
                <i class="fas fa-arrow-left mr-2"></i>Voltar ao Dashboard
            </a>
        </div>

        <!-- Filtro de Período -->
        <div class="content-card p-8 mb-8">
            <form method="GET" class="flex flex-wrap items-end gap-6">
                <div>
                    <label class="block text-white font-semibold mb-2">Período (dias)</label>
                    <select name="period" class="form-input">
                        <option value="7" {{ $period == 7 ? 'selected' : '' }}>Últimos 7 dias</option>
                        <option value="30" {{ $period == 30 ? 'selected' : '' }}>Últimos 30 dias</option>
                        <option value="90" {{ $period == 90 ? 'selected' : '' }}>Últimos 90 dias</option>
                        <option value="365" {{ $period == 365 ? 'selected' : '' }}>Último ano</option>
                    </select>
                </div>
                <button type="submit" class="btn-primary">
                    <i class="fas fa-chart-line mr-2"></i>Atualizar Relatório
                </button>
            </form>
        </div>

        <!-- Métricas Principais -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8 mb-12">
            <!-- Total Apostado -->
            <div class="content-card p-8 text-center">
                <div class="text-4xl text-blue-400 mb-4">
                    <i class="fas fa-coins"></i>
                </div>
                <h3 class="text-3xl font-bold text-white mb-2">€{{ number_format($reports['total_bets'], 2) }}</h3>
                <p class="text-gray-400">Total Apostado</p>
            </div>

            <!-- Total Pago -->
            <div class="content-card p-8 text-center">
                <div class="text-4xl text-red-400 mb-4">
                    <i class="fas fa-hand-holding-usd"></i>
                </div>
                <h3 class="text-3xl font-bold text-white mb-2">€{{ number_format($reports['total_wins'], 2) }}</h3>
                <p class="text-gray-400">Total Pago</p>
            </div>

            <!-- Lucro da Casa -->
            <div class="content-card p-8 text-center">
                <div class="text-4xl text-green-400 mb-4">
                    <i class="fas fa-chart-line"></i>
                </div>
                <h3 class="text-3xl font-bold text-white mb-2">€{{ number_format($reports['house_edge'], 2) }}</h3>
                <p class="text-gray-400">Lucro da Casa</p>
                <div class="mt-2">
                    <span class="text-sm {{ $reports['house_edge_percentage'] > 0 ? 'text-green-400' : 'text-red-400' }}">
                        {{ number_format($reports['house_edge_percentage'], 2) }}%
                    </span>
                </div>
            </div>

            <!-- Usuários Ativos -->
            <div class="content-card p-8 text-center">
                <div class="text-4xl text-purple-400 mb-4">
                    <i class="fas fa-users"></i>
                </div>
                <h3 class="text-3xl font-bold text-white mb-2">{{ number_format($reports['active_users']) }}</h3>
                <p class="text-gray-400">Usuários Ativos</p>
                <div class="mt-2">
                    <span class="text-sm text-blue-400">
                        +{{ $reports['new_users'] }} novos
                    </span>
                </div>
            </div>
        </div>

        <!-- Gráfico de Apostas Diárias -->
        <div class="content-card p-8 mb-8">
            <h3 class="text-2xl font-bold text-white mb-6">
                <i class="fas fa-chart-bar text-neon-green mr-3"></i>Apostas por Dia
            </h3>
            
            @if($dailyStats->count() > 0)
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <!-- Gráfico Visual Simples -->
                <div>
                    <h4 class="text-lg font-semibold text-white mb-4">Volume de Apostas</h4>
                    <div class="space-y-3">
                        @php
                            $maxBets = $dailyStats->max('total_bets');
                        @endphp
                        @foreach($dailyStats as $stat)
                        <div class="flex items-center gap-4">
                            <div class="w-20 text-sm text-gray-400">
                                {{ \Carbon\Carbon::parse($stat->date)->format('d/m') }}
                            </div>
                            <div class="flex-1 bg-gray-800 rounded-full h-6 relative">
                                <div class="bg-gradient-to-r from-neon-green to-blue-400 h-6 rounded-full transition-all duration-500"
                                     style="width: {{ $maxBets > 0 ? ($stat->total_bets / $maxBets) * 100 : 0 }}%"></div>
                                <span class="absolute inset-0 flex items-center justify-center text-xs font-bold text-white">
                                    €{{ number_format($stat->total_bets, 0) }}
                                </span>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>

                <!-- Tabela de Dados -->
                <div>
                    <h4 class="text-lg font-semibold text-white mb-4">Dados Detalhados</h4>
                    <div class="overflow-y-auto max-h-80">
                        <table class="w-full text-sm">
                            <thead class="bg-gray-800 sticky top-0">
                                <tr>
                                    <th class="px-4 py-2 text-left text-neon-green">Data</th>
                                    <th class="px-4 py-2 text-right text-neon-green">Apostas</th>
                                    <th class="px-4 py-2 text-right text-neon-green">Volume</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-700">
                                @foreach($dailyStats as $stat)
                                <tr class="hover:bg-gray-800">
                                    <td class="px-4 py-2 text-white">
                                        {{ \Carbon\Carbon::parse($stat->date)->format('d/m/Y') }}
                                    </td>
                                    <td class="px-4 py-2 text-right text-gray-300">
                                        {{ number_format($stat->bet_count) }}
                                    </td>
                                    <td class="px-4 py-2 text-right text-neon-green font-semibold">
                                        €{{ number_format($stat->total_bets, 2) }}
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            @else
            <div class="text-center py-12">
                <div class="text-6xl text-gray-600 mb-4">
                    <i class="fas fa-chart-line"></i>
                </div>
                <p class="text-xl text-gray-400">Nenhum dado disponível para o período selecionado</p>
            </div>
            @endif
        </div>

        <!-- Resumo Financeiro -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <!-- Indicadores de Performance -->
            <div class="content-card p-8">
                <h3 class="text-2xl font-bold text-white mb-6">
                    <i class="fas fa-tachometer-alt text-yellow-400 mr-3"></i>Indicadores de Performance
                </h3>
                <div class="space-y-6">
                    <div class="flex justify-between items-center p-4 bg-gray-800 rounded-lg">
                        <span class="text-gray-300">Taxa de Retorno aos Jogadores (RTP)</span>
                        <span class="text-xl font-bold {{ (100 - $reports['house_edge_percentage']) > 95 ? 'text-green-400' : 'text-yellow-400' }}">
                            {{ number_format(100 - $reports['house_edge_percentage'], 2) }}%
                        </span>
                    </div>
                    <div class="flex justify-between items-center p-4 bg-gray-800 rounded-lg">
                        <span class="text-gray-300">Margem da Casa</span>
                        <span class="text-xl font-bold text-neon-green">
                            {{ number_format($reports['house_edge_percentage'], 2) }}%
                        </span>
                    </div>
                    <div class="flex justify-between items-center p-4 bg-gray-800 rounded-lg">
                        <span class="text-gray-300">Receita por Usuário Ativo</span>
                        <span class="text-xl font-bold text-blue-400">
                            €{{ $reports['active_users'] > 0 ? number_format($reports['total_bets'] / $reports['active_users'], 2) : '0.00' }}
                        </span>
                    </div>
                </div>
            </div>

            <!-- Ações Rápidas -->
            <div class="content-card p-8">
                <h3 class="text-2xl font-bold text-white mb-6">
                    <i class="fas fa-tools text-orange-400 mr-3"></i>Ações Rápidas
                </h3>
                <div class="space-y-4">
                    <a href="{{ route('admin.users.index') }}" class="btn-outline w-full text-center py-3">
                        <i class="fas fa-users mr-2"></i>Ver Todos os Usuários
                    </a>
                    <a href="{{ route('admin.logs.index') }}" class="btn-outline w-full text-center py-3">
                        <i class="fas fa-list-alt mr-2"></i>Logs de Transações
                    </a>
                    <a href="{{ route('admin.config') }}" class="btn-outline w-full text-center py-3">
                        <i class="fas fa-cog mr-2"></i>Configurações
                    </a>
                    <button onclick="window.print()" class="btn-primary w-full py-3">
                        <i class="fas fa-print mr-2"></i>Imprimir Relatório
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
