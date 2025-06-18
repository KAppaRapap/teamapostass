@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card shadow-sm border-0">
                <div class="card-body p-4 p-md-5">
                    <div class="text-center mb-4">
                        <i class="fas fa-credit-card fa-3x text-primary mb-3"></i>
                        <h1 class="h3">Notificações de Pagamentos</h1>
                        <p class="text-muted">
                            Configure suas notificações relacionadas a pagamentos.
                        </p>
                    </div>

                    <form method="POST" action="{{ route('user.payment-preferences.update') }}">
                        @csrf
                        @method('PUT')

                        <!-- Payment Notifications -->
                        <div class="mb-4">
                            <h5 class="mb-3">
                                <i class="fas fa-bell me-2"></i> Notificações de Pagamento
                            </h5>
                            <div class="form-check mb-2">
                                <input type="checkbox" class="form-check-input" id="payment_success" 
                                       name="payment_success" {{ auth()->user()->payment_success ? 'checked' : '' }}>
                                <label class="form-check-label" for="payment_success">
                                    Pagamento bem-sucedido
                                </label>
                                <small class="text-muted d-block mt-1">
                                    Seja notificado sobre pagamentos bem-sucedidos.
                                </small>
                            </div>
                            <div class="form-check mb-2">
                                <input type="checkbox" class="form-check-input" id="payment_failed" 
                                       name="payment_failed" {{ auth()->user()->payment_failed ? 'checked' : '' }}>
                                <label class="form-check-label" for="payment_failed">
                                    Pagamento falhou
                                </label>
                                <small class="text-muted d-block mt-1">
                                    Receba notificações sobre pagamentos que falharam.
                                </small>
                            </div>
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" id="payment_refund" 
                                       name="payment_refund" {{ auth()->user()->payment_refund ? 'checked' : '' }}>
                                <label class="form-check-label" for="payment_refund">
                                    Reembolsos
                                </label>
                                <small class="text-muted d-block mt-1">
                                    Seja notificado sobre reembolsos.
                                </small>
                            </div>
                        </div>

                        <!-- Payment Alerts -->
                        <div class="mb-4">
                            <h5 class="mb-3">
                                <i class="fas fa-exclamation-circle me-2"></i> Alertas de Pagamento
                            </h5>
                            <div class="form-check mb-2">
                                <input type="checkbox" class="form-check-input" id="alert_limit" 
                                       name="alert_limit" {{ auth()->user()->alert_limit ? 'checked' : '' }}>
                                <label class="form-check-label" for="alert_limit">
                                    Limites de pagamento
                                </label>
                                <small class="text-muted d-block mt-1">
                                    Receba notificações sobre limites de pagamento.
                                </small>
                            </div>
                            <div class="form-check mb-2">
                                <input type="checkbox" class="form-check-input" id="alert_suspicious" 
                                       name="alert_suspicious" {{ auth()->user()->alert_suspicious ? 'checked' : '' }}>
                                <label class="form-check-label" for="alert_suspicious">
                                    Atividades suspeitas
                                </label>
                                <small class="text-muted d-block mt-1">
                                    Seja notificado sobre atividades suspeitas.
                                </small>
                            </div>
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" id="alert_method" 
                                       name="alert_method" {{ auth()->user()->alert_method ? 'checked' : '' }}>
                                <label class="form-check-label" for="alert_method">
                                    Alterações de método
                                </label>
                                <small class="text-muted d-block mt-1">
                                    Receba notificações sobre alterações de método.
                                </small>
                            </div>
                        </div>

                        <!-- Payment Updates -->
                        <div class="mb-4">
                            <h5 class="mb-3">
                                <i class="fas fa-sync me-2"></i> Atualizações de Pagamento
                            </h5>
                            <div class="form-check mb-2">
                                <input type="checkbox" class="form-check-input" id="update_status" 
                                       name="update_status" {{ auth()->user()->update_status ? 'checked' : '' }}>
                                <label class="form-check-label" for="update_status">
                                    Status do pagamento
                                </label>
                                <small class="text-muted d-block mt-1">
                                    Seja notificado sobre status de pagamento.
                                </small>
                            </div>
                            <div class="form-check mb-2">
                                <input type="checkbox" class="form-check-input" id="update_receipt" 
                                       name="update_receipt" {{ auth()->user()->update_receipt ? 'checked' : '' }}>
                                <label class="form-check-label" for="update_receipt">
                                    Comprovantes
                                </label>
                                <small class="text-muted d-block mt-1">
                                    Receba notificações sobre comprovantes.
                                </small>
                            </div>
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" id="update_invoice" 
                                       name="update_invoice" {{ auth()->user()->update_invoice ? 'checked' : '' }}>
                                <label class="form-check-label" for="update_invoice">
                                    Faturas
                                </label>
                                <small class="text-muted d-block mt-1">
                                    Seja notificado sobre faturas.
                                </small>
                            </div>
                        </div>

                        <!-- Payment Methods -->
                        <div class="mb-4">
                            <h5 class="mb-3">
                                <i class="fas fa-wallet me-2"></i> Métodos de Pagamento
                            </h5>
                            <div class="form-check mb-2">
                                <input type="checkbox" class="form-check-input" id="method_added" 
                                       name="method_added" {{ auth()->user()->method_added ? 'checked' : '' }}>
                                <label class="form-check-label" for="method_added">
                                    Método adicionado
                                </label>
                                <small class="text-muted d-block mt-1">
                                    Receba notificações sobre métodos adicionados.
                                </small>
                            </div>
                            <div class="form-check mb-2">
                                <input type="checkbox" class="form-check-input" id="method_removed" 
                                       name="method_removed" {{ auth()->user()->method_removed ? 'checked' : '' }}>
                                <label class="form-check-label" for="method_removed">
                                    Método removido
                                </label>
                                <small class="text-muted d-block mt-1">
                                    Seja notificado sobre métodos removidos.
                                </small>
                            </div>
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" id="method_expired" 
                                       name="method_expired" {{ auth()->user()->method_expired ? 'checked' : '' }}>
                                <label class="form-check-label" for="method_expired">
                                    Método expirado
                                </label>
                                <small class="text-muted d-block mt-1">
                                    Receba notificações sobre métodos expirados.
                                </small>
                            </div>
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-2"></i> Salvar Preferências
                            </button>
                        </div>
                    </form>
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
                            <i class="fas fa-check-circle me-2"></i> Mantenha-se informado sobre seus pagamentos
                        </li>
                        <li class="mb-2">
                            <i class="fas fa-check-circle me-2"></i> Configure alertas para transações importantes
                        </li>
                        <li>
                            <i class="fas fa-check-circle me-2"></i> Você pode alterar estas configurações a qualquer momento
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.card {
    border-radius: 16px;
}

.form-control {
    border-radius: 8px;
    padding: 0.75rem 1rem;
}

.form-control:focus {
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

.form-check-input:checked {
    background-color: var(--primary-color);
    border-color: var(--primary-color);
}

.form-check-input:focus {
    box-shadow: 0 0 0 0.2rem rgba(25, 118, 210, 0.15);
}

.list-unstyled li {
    font-size: 0.9rem;
}

small.text-muted {
    font-size: 0.85rem;
}
</style>
@endsection 