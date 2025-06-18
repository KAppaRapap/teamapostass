@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card shadow-sm border-0">
                <div class="card-body p-4 p-md-5">
                    <div class="text-center mb-4">
                        <i class="fas fa-bell fa-3x text-primary mb-3"></i>
                        <h1 class="h3">Configurações de Notificações</h1>
                        <p class="text-muted">
                            Escolha como você deseja receber as notificações.
                        </p>
                    </div>

                    <form method="POST" action="{{ route('user.notification-preferences.update') }}">
                        @csrf
                        @method('PUT')

                        <!-- Email Notifications -->
                        <div class="mb-4">
                            <h5 class="mb-3">
                                <i class="fas fa-envelope me-2"></i> Notificações por E-mail
                            </h5>
                            <div class="form-check mb-2">
                                <input type="checkbox" class="form-check-input" id="email_bets" 
                                       name="email_bets" {{ auth()->user()->email_bets ? 'checked' : '' }}>
                                <label class="form-check-label" for="email_bets">
                                    Apostas e resultados
                                </label>
                            </div>
                            <div class="form-check mb-2">
                                <input type="checkbox" class="form-check-input" id="email_groups" 
                                       name="email_groups" {{ auth()->user()->email_groups ? 'checked' : '' }}>
                                <label class="form-check-label" for="email_groups">
                                    Atividades do grupo
                                </label>
                            </div>
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" id="email_balance" 
                                       name="email_balance" {{ auth()->user()->email_balance ? 'checked' : '' }}>
                                <label class="form-check-label" for="email_balance">
                                    Atualizações de saldo
                                </label>
                            </div>
                        </div>

                        <!-- Push Notifications -->
                        <div class="mb-4">
                            <h5 class="mb-3">
                                <i class="fas fa-mobile-alt me-2"></i> Notificações Push
                            </h5>
                            <div class="form-check mb-2">
                                <input type="checkbox" class="form-check-input" id="push_bets" 
                                       name="push_bets" {{ auth()->user()->push_bets ? 'checked' : '' }}>
                                <label class="form-check-label" for="push_bets">
                                    Apostas e resultados
                                </label>
                            </div>
                            <div class="form-check mb-2">
                                <input type="checkbox" class="form-check-input" id="push_groups" 
                                       name="push_groups" {{ auth()->user()->push_groups ? 'checked' : '' }}>
                                <label class="form-check-label" for="push_groups">
                                    Atividades do grupo
                                </label>
                            </div>
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" id="push_balance" 
                                       name="push_balance" {{ auth()->user()->push_balance ? 'checked' : '' }}>
                                <label class="form-check-label" for="push_balance">
                                    Atualizações de saldo
                                </label>
                            </div>
                        </div>

                        <!-- SMS Notifications -->
                        <div class="mb-4">
                            <h5 class="mb-3">
                                <i class="fas fa-sms me-2"></i> Notificações por SMS
                            </h5>
                            <div class="form-check mb-2">
                                <input type="checkbox" class="form-check-input" id="sms_bets" 
                                       name="sms_bets" {{ auth()->user()->sms_bets ? 'checked' : '' }}>
                                <label class="form-check-label" for="sms_bets">
                                    Apostas e resultados
                                </label>
                            </div>
                            <div class="form-check mb-2">
                                <input type="checkbox" class="form-check-input" id="sms_groups" 
                                       name="sms_groups" {{ auth()->user()->sms_groups ? 'checked' : '' }}>
                                <label class="form-check-label" for="sms_groups">
                                    Atividades do grupo
                                </label>
                            </div>
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" id="sms_balance" 
                                       name="sms_balance" {{ auth()->user()->sms_balance ? 'checked' : '' }}>
                                <label class="form-check-label" for="sms_balance">
                                    Atualizações de saldo
                                </label>
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

            <!-- Notification Tips -->
            <div class="card shadow-sm border-0 mt-4">
                <div class="card-body p-4">
                    <h5 class="card-title mb-3">
                        <i class="fas fa-info-circle me-2"></i> Dicas
                    </h5>
                    <ul class="list-unstyled text-muted mb-0">
                        <li class="mb-2">
                            <i class="fas fa-check-circle me-2"></i> Mantenha as notificações ativas para não perder nada
                        </li>
                        <li class="mb-2">
                            <i class="fas fa-check-circle me-2"></i> Escolha o método que melhor se adapta ao seu dia a dia
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
</style>
@endsection 