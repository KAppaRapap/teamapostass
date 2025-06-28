@extends('layouts.app')

@section('title', 'Carteira')
@section('description', 'Gerencia o teu saldo e visualiza as tuas transações')

@section('content')
<div class="py-8 px-6">
    <div class="max-w-7xl mx-auto">
        <!-- Header -->
        <div class="mb-8">
            <h1 class="font-orbitron font-bold text-3xl lg:text-4xl mb-2">
                <span class="text-neon-green">Carteira</span> Virtual
            </h1>
            <p class="text-gray-300">Gerencia o teu saldo e visualiza as tuas transações</p>
        </div>

        <div class="grid lg:grid-cols-3 gap-8">
            <!-- Saldo e Ações -->
            <div class="lg:col-span-1 space-y-6">
                <!-- Saldo Atual -->
                <div class="content-card text-center">
                    <div class="mb-6">
                        <div class="text-4xl font-bold text-neon-green mb-2">
                            €{{ number_format($user->virtual_balance, 2) }}
                        </div>
                        <p class="text-gray-400">Saldo Atual</p>
                    </div>

                    <div class="flex gap-3">
                        <a href="{{ route('wallet.deposit') }}" class="btn-primary flex-1">
                            <i class="fas fa-plus mr-2"></i> Depositar
                        </a>
                    </div>
                </div>

                <!-- Estatísticas -->
                <div class="content-card">
                    <h3 class="font-semibold text-lg mb-4">Estatísticas</h3>
                    <div class="space-y-4">
                        <div class="flex justify-between items-center">
                            <span class="text-gray-400">Total Depositado</span>
                            <span class="font-semibold text-white">
                                €{{ number_format($user->activities()->where('type', 'balance_updated')->where('data->type', 'credit')->sum('data->amount'), 2) }}
                            </span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-gray-400">Total em Apostas</span>
                            <span class="font-semibold text-white">
                                €{{ number_format($user->activities()->where('type', 'bet_placed')->sum('data->amount'), 2) }}
                            </span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-gray-400">Total Ganho</span>
                            <span class="font-semibold text-neon-green">
                                €{{ number_format($user->activities()->where('type', 'bet_won')->sum('data->prize_amount'), 2) }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Histórico de Transações -->
            <div class="lg:col-span-2">
                <div class="content-card">
                    <h3 class="font-semibold text-lg mb-6">Histórico de Transações</h3>
                    
                    <div class="overflow-x-auto">
                        <table class="table-modern w-full">
                            <thead>
                                <tr>
                                    <th>Data</th>
                                    <th>Descrição</th>
                                    <th>Valor</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($transactions as $transaction)
                                <tr>
                                    <td class="text-sm">{{ $transaction->created_at->format('d/m/Y H:i') }}</td>
                                    <td class="font-medium">{{ $transaction->description }}</td>
                                    <td>
                                        @switch($transaction->type)
                                            @case('bet_placed')
                                                <span class="text-red-400 font-semibold">-€{{ number_format($transaction->data['amount'], 2) }}</span>
                                                @break
                                            @case('bet_won')
                                                @php
                                                    $prize = $transaction->data['prize_amount'] ?? $transaction->data['amount'] ?? 0;
                                                @endphp
                                                <span class="text-neon-green font-semibold">+€{{ number_format($prize, 2) }}</span>
                                                @break
                                            @case('balance_updated')
                                                @if($transaction->data['type'] === 'credit')
                                                    <span class="text-neon-green font-semibold">+€{{ number_format($transaction->data['amount'], 2) }}</span>
                                                @else
                                                    <span class="text-red-400 font-semibold">-€{{ number_format($transaction->data['amount'], 2) }}</span>
                                                @endif
                                                @break
                                        @endswitch
                                    </td>
                                    <td>
                                        @switch($transaction->type)
                                            @case('bet_placed')
                                                <span class="px-2 py-1 bg-blue-500 text-white text-xs rounded">Aposta</span>
                                                @break
                                            @case('bet_won')
                                                <span class="px-2 py-1 bg-green-500 text-white text-xs rounded">Ganho</span>
                                                @break
                                            @case('balance_updated')
                                                @if($transaction->data['type'] === 'credit')
                                                    <span class="px-2 py-1 bg-neon-green text-dark-bg text-xs rounded">Depósito</span>
                                                @else
                                                    <span class="px-2 py-1 bg-yellow-500 text-white text-xs rounded">Saque</span>
                                                @endif
                                                @break
                                        @endswitch
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="text-center py-12">
                                        <div class="w-16 h-16 bg-gradient-to-br from-gray-500 to-gray-600 rounded-full flex items-center justify-center mx-auto mb-4">
                                            <i class="fas fa-history text-2xl text-white"></i>
                                        </div>
                                        <p class="text-gray-400">Nenhuma transação encontrada</p>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    
                    @if($transactions->hasPages())
                    <div class="mt-6 flex justify-center">
                        {{ $transactions->links() }}
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Sacar Saldo -->
@endsection

@push('scripts')
<script>
    // Sistema de atualização automática já está implementado globalmente
    // Não é necessário recarregar a página
</script>
@endpush