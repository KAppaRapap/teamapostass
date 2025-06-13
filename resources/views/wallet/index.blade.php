@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row mb-4">
        <div class="col-12">
            <h1 class="h3 mb-0">Carteira</h1>
            <p class="text-muted">Gerencie seu saldo e visualize suas transações</p>
        </div>
    </div>

    <div class="row g-4">
        <!-- Saldo e Ações -->
        <div class="col-lg-4">
            <div class="card">
                <div class="card-body">
                    <div class="text-center mb-4">
                        <div class="display-4 mb-2">€{{ number_format($user->virtual_balance, 2) }}</div>
                        <p class="text-muted mb-0">Saldo Atual</p>
                    </div>

                    <div class="d-grid gap-2">
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addFundsModal">
                            <i class="fas fa-plus me-2"></i> Adicionar Saldo
                        </button>
                        <button type="button" class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#withdrawFundsModal">
                            <i class="fas fa-minus me-2"></i> Sacar Saldo
                        </button>
                    </div>
                </div>
            </div>

            <!-- Estatísticas -->
            <div class="card mt-4">
                <div class="card-body">
                    <h5 class="card-title mb-3">Estatísticas</h5>
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted">Total Depositado</span>
                        <span class="fw-bold">€{{ number_format($user->activities()->where('type', 'balance_updated')->where('data->type', 'credit')->sum('data->amount'), 2) }}</span>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted">Total Sacado</span>
                        <span class="fw-bold">€{{ number_format($user->activities()->where('type', 'balance_updated')->where('data->type', 'debit')->sum('data->amount'), 2) }}</span>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted">Total em Apostas</span>
                        <span class="fw-bold">€{{ number_format($user->activities()->where('type', 'bet_placed')->sum('data->amount'), 2) }}</span>
                    </div>
                    <div class="d-flex justify-content-between">
                        <span class="text-muted">Total Ganho</span>
                        <span class="fw-bold text-success">€{{ number_format($user->activities()->where('type', 'bet_won')->sum('data->prize_amount'), 2) }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Histórico de Transações -->
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header bg-transparent border-0">
                    <h5 class="card-title mb-0">Histórico de Transações</h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
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
                                    <td>{{ $transaction->created_at->format('d/m/Y H:i') }}</td>
                                    <td>{{ $transaction->description }}</td>
                                    <td>
                                        @switch($transaction->type)
                                            @case('bet_placed')
                                                <span class="text-danger">-€{{ number_format($transaction->data['amount'], 2) }}</span>
                                                @break
                                            @case('bet_won')
                                                <span class="text-success">+€{{ number_format($transaction->data['prize_amount'], 2) }}</span>
                                                @break
                                            @case('balance_updated')
                                                @if($transaction->data['type'] === 'credit')
                                                    <span class="text-success">+€{{ number_format($transaction->data['amount'], 2) }}</span>
                                                @else
                                                    <span class="text-danger">-€{{ number_format($transaction->data['amount'], 2) }}</span>
                                                @endif
                                                @break
                                        @endswitch
                                    </td>
                                    <td>
                                        @switch($transaction->type)
                                            @case('bet_placed')
                                                <span class="badge bg-primary">Aposta</span>
                                                @break
                                            @case('bet_won')
                                                <span class="badge bg-success">Ganho</span>
                                                @break
                                            @case('balance_updated')
                                                @if($transaction->data['type'] === 'credit')
                                                    <span class="badge bg-info">Depósito</span>
                                                @else
                                                    <span class="badge bg-warning">Saque</span>
                                                @endif
                                                @break
                                        @endswitch
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="text-center py-4">
                                        <div class="text-muted">
                                            <i class="fas fa-history fa-2x mb-2"></i>
                                            <p class="mb-0">Nenhuma transação encontrada</p>
                                        </div>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                @if($transactions->hasPages())
                <div class="card-footer bg-transparent border-0">
                    {{ $transactions->links() }}
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Modal Adicionar Saldo -->
<div class="modal fade" id="addFundsModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('wallet.add-funds') }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Adicionar Saldo</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="amount" class="form-label">Valor (€)</label>
                        <input type="number" class="form-control" id="amount" name="amount" min="1" max="1000" step="0.01" required>
                        <div class="form-text">Valor mínimo: €1,00 | Valor máximo: €1.000,00</div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Confirmar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Sacar Saldo -->
<div class="modal fade" id="withdrawFundsModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('wallet.withdraw-funds') }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Sacar Saldo</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="withdraw_amount" class="form-label">Valor (€)</label>
                        <input type="number" class="form-control" id="withdraw_amount" name="amount" min="1" max="1000" step="0.01" required>
                        <div class="form-text">Valor mínimo: €1,00 | Valor máximo: €1.000,00</div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Confirmar</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Atualizar saldo a cada 30 segundos
    setInterval(function() {
        location.reload();
    }, 30000);
</script>
@endpush 