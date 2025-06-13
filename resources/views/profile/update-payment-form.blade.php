@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card shadow-sm border-0">
                <div class="card-body p-4 p-md-5">
                    <div class="text-center mb-4">
                        <i class="fas fa-credit-card fa-3x text-primary mb-3"></i>
                        <h1 class="h3">Configurações de Pagamento</h1>
                        <p class="text-muted">
                            Gerencie seus métodos de pagamento.
                        </p>
                    </div>

                    <!-- Payment Methods -->
                    <div class="mb-4">
                        <h5 class="mb-3">
                            <i class="fas fa-wallet me-2"></i> Métodos de Pagamento
                        </h5>
                        
                        <!-- Credit Cards -->
                        <div class="mb-4">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h6 class="mb-0">Cartões de Crédito</h6>
                                <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#addCardModal">
                                    <i class="fas fa-plus me-2"></i>Adicionar Cartão
                                </button>
                            </div>
                            
                            @if(auth()->user()->cards->count() > 0)
                                <div class="list-group">
                                    @foreach(auth()->user()->cards as $card)
                                        <div class="list-group-item d-flex justify-content-between align-items-center">
                                            <div>
                                                <i class="fab fa-cc-{{ strtolower($card->brand) }} fa-2x me-3"></i>
                                                <span class="fw-bold">**** **** **** {{ $card->last4 }}</span>
                                                <small class="text-muted d-block">Expira em {{ $card->exp_month }}/{{ $card->exp_year }}</small>
                                            </div>
                                            <div class="btn-group">
                                                <button type="button" class="btn btn-sm btn-outline-primary" 
                                                        data-bs-toggle="modal" data-bs-target="#editCardModal" 
                                                        data-card-id="{{ $card->id }}">
                                                    <i class="fas fa-edit"></i>
                                                </button>
                                                <button type="button" class="btn btn-sm btn-outline-danger" 
                                                        data-bs-toggle="modal" data-bs-target="#deleteCardModal" 
                                                        data-card-id="{{ $card->id }}">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <div class="alert alert-info">
                                    <i class="fas fa-info-circle me-2"></i>
                                    Você ainda não adicionou nenhum cartão de crédito.
                                </div>
                            @endif
                        </div>

                        <!-- Bank Accounts -->
                        <div class="mb-4">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h6 class="mb-0">Contas Bancárias</h6>
                                <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#addBankModal">
                                    <i class="fas fa-plus me-2"></i>Adicionar Conta
                                </button>
                            </div>
                            
                            @if(auth()->user()->bankAccounts->count() > 0)
                                <div class="list-group">
                                    @foreach(auth()->user()->bankAccounts as $account)
                                        <div class="list-group-item d-flex justify-content-between align-items-center">
                                            <div>
                                                <i class="fas fa-university fa-2x me-3"></i>
                                                <span class="fw-bold">{{ $account->bank_name }}</span>
                                                <small class="text-muted d-block">Agência: {{ $account->agency }} | Conta: {{ $account->account }}</small>
                                            </div>
                                            <div class="btn-group">
                                                <button type="button" class="btn btn-sm btn-outline-primary" 
                                                        data-bs-toggle="modal" data-bs-target="#editBankModal" 
                                                        data-account-id="{{ $account->id }}">
                                                    <i class="fas fa-edit"></i>
                                                </button>
                                                <button type="button" class="btn btn-sm btn-outline-danger" 
                                                        data-bs-toggle="modal" data-bs-target="#deleteBankModal" 
                                                        data-account-id="{{ $account->id }}">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <div class="alert alert-info">
                                    <i class="fas fa-info-circle me-2"></i>
                                    Você ainda não adicionou nenhuma conta bancária.
                                </div>
                            @endif
                        </div>

                        <!-- PIX -->
                        <div class="mb-4">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h6 class="mb-0">Chaves PIX</h6>
                                <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#addPixModal">
                                    <i class="fas fa-plus me-2"></i>Adicionar Chave
                                </button>
                            </div>
                            
                            @if(auth()->user()->pixKeys->count() > 0)
                                <div class="list-group">
                                    @foreach(auth()->user()->pixKeys as $pix)
                                        <div class="list-group-item d-flex justify-content-between align-items-center">
                                            <div>
                                                <i class="fas fa-qrcode fa-2x me-3"></i>
                                                <span class="fw-bold">{{ $pix->type }}</span>
                                                <small class="text-muted d-block">{{ $pix->key }}</small>
                                            </div>
                                            <div class="btn-group">
                                                <button type="button" class="btn btn-sm btn-outline-primary" 
                                                        data-bs-toggle="modal" data-bs-target="#editPixModal" 
                                                        data-pix-id="{{ $pix->id }}">
                                                    <i class="fas fa-edit"></i>
                                                </button>
                                                <button type="button" class="btn btn-sm btn-outline-danger" 
                                                        data-bs-toggle="modal" data-bs-target="#deletePixModal" 
                                                        data-pix-id="{{ $pix->id }}">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <div class="alert alert-info">
                                    <i class="fas fa-info-circle me-2"></i>
                                    Você ainda não adicionou nenhuma chave PIX.
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Payment History -->
                    <div class="mb-4">
                        <h5 class="mb-3">
                            <i class="fas fa-history me-2"></i> Histórico de Pagamentos
                        </h5>
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Data</th>
                                        <th>Descrição</th>
                                        <th>Valor</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse(auth()->user()->payments as $payment)
                                        <tr>
                                            <td>{{ $payment->created_at->format('d/m/Y H:i') }}</td>
                                            <td>{{ $payment->description }}</td>
                                            <td>R$ {{ number_format($payment->amount, 2, ',', '.') }}</td>
                                            <td>
                                                <span class="badge bg-{{ $payment->status_color }}">
                                                    {{ $payment->status_text }}
                                                </span>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4" class="text-center">
                                                Nenhum pagamento encontrado.
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Payment Tips -->
            <div class="card shadow-sm border-0 mt-4">
                <div class="card-body p-4">
                    <h5 class="card-title mb-3">
                        <i class="fas fa-info-circle me-2"></i> Dicas
                    </h5>
                    <ul class="list-unstyled text-muted mb-0">
                        <li class="mb-2">
                            <i class="fas fa-check-circle me-2"></i> Mantenha seus dados de pagamento atualizados
                        </li>
                        <li class="mb-2">
                            <i class="fas fa-check-circle me-2"></i> Verifique sempre o status das suas transações
                        </li>
                        <li>
                            <i class="fas fa-check-circle me-2"></i> Em caso de dúvidas, entre em contato com o suporte
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Add Card Modal -->
<div class="modal fade" id="addCardModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Adicionar Cartão</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="addCardForm">
                    <div class="mb-3">
                        <label for="card_number" class="form-label">Número do Cartão</label>
                        <input type="text" class="form-control" id="card_number" required>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="expiry" class="form-label">Validade</label>
                            <input type="text" class="form-control" id="expiry" placeholder="MM/AA" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="cvv" class="form-label">CVV</label>
                            <input type="text" class="form-control" id="cvv" required>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="card_name" class="form-label">Nome no Cartão</label>
                        <input type="text" class="form-control" id="card_name" required>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary">Adicionar</button>
            </div>
        </div>
    </div>
</div>

<!-- Add Bank Modal -->
<div class="modal fade" id="addBankModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Adicionar Conta Bancária</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="addBankForm">
                    <div class="mb-3">
                        <label for="bank_name" class="form-label">Banco</label>
                        <input type="text" class="form-control" id="bank_name" required>
                    </div>
                    <div class="mb-3">
                        <label for="agency" class="form-label">Agência</label>
                        <input type="text" class="form-control" id="agency" required>
                    </div>
                    <div class="mb-3">
                        <label for="account" class="form-label">Conta</label>
                        <input type="text" class="form-control" id="account" required>
                    </div>
                    <div class="mb-3">
                        <label for="account_type" class="form-label">Tipo de Conta</label>
                        <select class="form-select" id="account_type" required>
                            <option value="checking">Conta Corrente</option>
                            <option value="savings">Conta Poupança</option>
                        </select>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary">Adicionar</button>
            </div>
        </div>
    </div>
</div>

<!-- Add PIX Modal -->
<div class="modal fade" id="addPixModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Adicionar Chave PIX</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="addPixForm">
                    <div class="mb-3">
                        <label for="pix_type" class="form-label">Tipo de Chave</label>
                        <select class="form-select" id="pix_type" required>
                            <option value="cpf">CPF</option>
                            <option value="email">E-mail</option>
                            <option value="phone">Telefone</option>
                            <option value="random">Chave Aleatória</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="pix_key" class="form-label">Chave PIX</label>
                        <input type="text" class="form-control" id="pix_key" required>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary">Adicionar</button>
            </div>
        </div>
    </div>
</div>

<style>
.card {
    border-radius: 16px;
}

.form-control, .form-select {
    border-radius: 8px;
    padding: 0.75rem 1rem;
}

.form-control:focus, .form-select:focus {
    box-shadow: 0 0 0 0.2rem rgba(25, 118, 210, 0.15);
}

.btn {
    padding: 0.75rem 1.5rem;
    font-weight: 500;
    border-radius: 8px;
}

.btn-primary {
    background-color: var(--primary-color);
    border-color: var(--primary-color);
}

.btn-primary:hover {
    background-color: #1565c0;
    border-color: #1565c0;
}

.list-group-item {
    border-radius: 8px;
    margin-bottom: 0.5rem;
}

.table {
    border-radius: 8px;
    overflow: hidden;
}

.badge {
    padding: 0.5rem 0.75rem;
    border-radius: 6px;
}

.modal-content {
    border-radius: 16px;
}

.modal-header {
    border-bottom: 1px solid rgba(0,0,0,0.1);
}

.modal-footer {
    border-top: 1px solid rgba(0,0,0,0.1);
}

.list-unstyled li {
    font-size: 0.9rem;
}
</style>
@endsection 