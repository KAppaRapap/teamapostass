@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card shadow-sm border-0">
                <div class="card-body p-4 p-md-5">
                    <div class="text-center mb-4">
                        <i class="fas fa-sms fa-3x text-primary mb-3"></i>
                        <h1 class="h3">Notificações SMS</h1>
                        <p class="text-muted">
                            Configure suas notificações por SMS.
                        </p>
                    </div>

                    <form method="POST" action="{{ route('user.sms-preferences.update') }}">
                        @csrf
                        @method('PUT')

                        <!-- Phone Number -->
                        <div class="mb-4">
                            <h5 class="mb-3">
                                <i class="fas fa-phone me-2"></i> Número de Telefone
                            </h5>
                            <div class="mb-3">
                                <label for="phone" class="form-label">Número para SMS</label>
                                <div class="input-group">
                                    <span class="input-group-text">+55</span>
                                    <input type="tel" class="form-control" id="phone" name="phone" 
                                           value="{{ auth()->user()->phone }}" required>
                                </div>
                                <small class="text-muted d-block mt-2">
                                    Este número será usado para enviar as notificações SMS.
                                </small>
                            </div>
                        </div>

                        <!-- Account Notifications -->
                        <div class="mb-4">
                            <h5 class="mb-3">
                                <i class="fas fa-user-shield me-2"></i> Notificações da Conta
                            </h5>
                            <div class="form-check mb-2">
                                <input type="checkbox" class="form-check-input" id="sms_login" 
                                       name="sms_login" {{ auth()->user()->sms_login ? 'checked' : '' }}>
                                <label class="form-check-label" for="sms_login">
                                    Alertas de login
                                </label>
                                <small class="text-muted d-block mt-1">
                                    Receba SMS quando sua conta for acessada.
                                </small>
                            </div>
                            <div class="form-check mb-2">
                                <input type="checkbox" class="form-check-input" id="sms_password" 
                                       name="sms_password" {{ auth()->user()->sms_password ? 'checked' : '' }}>
                                <label class="form-check-label" for="sms_password">
                                    Alterações de palavra-passe
                                </label>
                                <small class="text-muted d-block mt-1">
                                    Seja notificado sobre alterações na sua palavra-passe.
                                </small>
                            </div>
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" id="sms_security" 
                                       name="sms_security" {{ auth()->user()->sms_security ? 'checked' : '' }}>
                                <label class="form-check-label" for="sms_security">
                                    Alertas de segurança
                                </label>
                                <small class="text-muted d-block mt-1">
                                    Receba alertas sobre atividades suspeitas.
                                </small>
                            </div>
                        </div>

                        <!-- Betting Notifications -->
                        <div class="mb-4">
                            <h5 class="mb-3">
                                <i class="fas fa-dice me-2"></i> Notificações de Apostas
                            </h5>
                            <div class="form-check mb-2">
                                <input type="checkbox" class="form-check-input" id="sms_bet_placed" 
                                       name="sms_bet_placed" {{ auth()->user()->sms_bet_placed ? 'checked' : '' }}>
                                <label class="form-check-label" for="sms_bet_placed">
                                    Apostas realizadas
                                </label>
                                <small class="text-muted d-block mt-1">
                                    Confirmação quando uma aposta for realizada.
                                </small>
                            </div>
                            <div class="form-check mb-2">
                                <input type="checkbox" class="form-check-input" id="sms_bet_result" 
                                       name="sms_bet_result" {{ auth()->user()->sms_bet_result ? 'checked' : '' }}>
                                <label class="form-check-label" for="sms_bet_result">
                                    Resultados de apostas
                                </label>
                                <small class="text-muted d-block mt-1">
                                    Seja notificado sobre os resultados das suas apostas.
                                </small>
                            </div>
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" id="sms_bet_reminder" 
                                       name="sms_bet_reminder" {{ auth()->user()->sms_bet_reminder ? 'checked' : '' }}>
                                <label class="form-check-label" for="sms_bet_reminder">
                                    Lembretes de apostas
                                </label>
                                <small class="text-muted d-block mt-1">
                                    Receba lembretes sobre apostas pendentes.
                                </small>
                            </div>
                        </div>

                        <!-- Group Notifications -->
                        <div class="mb-4">
                            <h5 class="mb-3">
                                <i class="fas fa-users me-2"></i> Notificações de Grupos
                            </h5>
                            <div class="form-check mb-2">
                                <input type="checkbox" class="form-check-input" id="sms_group_invite" 
                                       name="sms_group_invite" {{ auth()->user()->sms_group_invite ? 'checked' : '' }}>
                                <label class="form-check-label" for="sms_group_invite">
                                    Convites para grupos
                                </label>
                                <small class="text-muted d-block mt-1">
                                    Seja notificado quando receber um convite.
                                </small>
                            </div>
                            <div class="form-check mb-2">
                                <input type="checkbox" class="form-check-input" id="sms_group_activity" 
                                       name="sms_group_activity" {{ auth()->user()->sms_group_activity ? 'checked' : '' }}>
                                <label class="form-check-label" for="sms_group_activity">
                                    Atividades do grupo
                                </label>
                                <small class="text-muted d-block mt-1">
                                    Receba atualizações sobre atividades no grupo.
                                </small>
                            </div>
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" id="sms_group_message" 
                                       name="sms_group_message" {{ auth()->user()->sms_group_message ? 'checked' : '' }}>
                                <label class="form-check-label" for="sms_group_message">
                                    Mensagens do grupo
                                </label>
                                <small class="text-muted d-block mt-1">
                                    Seja notificado sobre novas mensagens.
                                </small>
                            </div>
                        </div>

                        <!-- Marketing Notifications -->
                        <div class="mb-4">
                            <h5 class="mb-3">
                                <i class="fas fa-bullhorn me-2"></i> Notificações de Marketing
                            </h5>
                            <div class="form-check mb-2">
                                <input type="checkbox" class="form-check-input" id="sms_promotions" 
                                       name="sms_promotions" {{ auth()->user()->sms_promotions ? 'checked' : '' }}>
                                <label class="form-check-label" for="sms_promotions">
                                    Promoções e ofertas
                                </label>
                                <small class="text-muted d-block mt-1">
                                    Receba SMS sobre promoções especiais.
                                </small>
                            </div>
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" id="sms_newsletter" 
                                       name="sms_newsletter" {{ auth()->user()->sms_newsletter ? 'checked' : '' }}>
                                <label class="form-check-label" for="sms_newsletter">
                                    Newsletter
                                </label>
                                <small class="text-muted d-block mt-1">
                                    Receba SMS sobre novidades e dicas.
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

            <!-- SMS Tips -->
            <div class="card shadow-sm border-0 mt-4">
                <div class="card-body p-4">
                    <h5 class="card-title mb-3">
                        <i class="fas fa-info-circle me-2"></i> Dicas
                    </h5>
                    <ul class="list-unstyled text-muted mb-0">
                        <li class="mb-2">
                            <i class="fas fa-check-circle me-2"></i> Mantenha seu número de telefone atualizado
                        </li>
                        <li class="mb-2">
                            <i class="fas fa-check-circle me-2"></i> Escolha apenas as notificações mais importantes
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

.input-group-text {
    border-radius: 8px 0 0 8px;
}

.input-group .form-control {
    border-radius: 0 8px 8px 0;
}
</style>
@endsection 