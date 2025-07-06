@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card shadow-sm border-0">
                <div class="card-body p-4 p-md-5">
                    <div class="text-center mb-4">
                        <i class="fas fa-bell fa-3x text-primary mb-3"></i>
                        <h1 class="h3">Notificações Push</h1>
                        <p class="text-muted">
                            Configure suas notificações no dispositivo.
                        </p>
                    </div>

                    <form method="POST" action="{{ route('user.push-preferences.update') }}">
                        @csrf
                        @method('PUT')

                        <!-- Account Notifications -->
                        <div class="mb-4">
                            <h5 class="mb-3">
                                <i class="fas fa-user-shield me-2"></i> Notificações da Conta
                            </h5>
                            <div class="form-check mb-2">
                                <input type="checkbox" class="form-check-input" id="push_login" 
                                       name="push_login" {{ auth()->user()->push_login ? 'checked' : '' }}>
                                <label class="form-check-label" for="push_login">
                                    Alertas de login
                                </label>
                                <small class="text-muted d-block mt-1">
                                    Receba notificações quando sua conta for acessada.
                                </small>
                            </div>
                            <div class="form-check mb-2">
                                <input type="checkbox" class="form-check-input" id="push_password" 
                                       name="push_password" {{ auth()->user()->push_password ? 'checked' : '' }}>
                                <label class="form-check-label" for="push_password">
                                    Alterações de palavra-passe
                                </label>
                                <small class="text-muted d-block mt-1">
                                    Seja notificado sobre alterações na sua palavra-passe.
                                </small>
                            </div>
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" id="push_security" 
                                       name="push_security" {{ auth()->user()->push_security ? 'checked' : '' }}>
                                <label class="form-check-label" for="push_security">
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
                                <input type="checkbox" class="form-check-input" id="push_bet_placed" 
                                       name="push_bet_placed" {{ auth()->user()->push_bet_placed ? 'checked' : '' }}>
                                <label class="form-check-label" for="push_bet_placed">
                                    Apostas realizadas
                                </label>
                                <small class="text-muted d-block mt-1">
                                    Confirmação quando uma aposta for realizada.
                                </small>
                            </div>
                            <div class="form-check mb-2">
                                <input type="checkbox" class="form-check-input" id="push_bet_result" 
                                       name="push_bet_result" {{ auth()->user()->push_bet_result ? 'checked' : '' }}>
                                <label class="form-check-label" for="push_bet_result">
                                    Resultados de apostas
                                </label>
                                <small class="text-muted d-block mt-1">
                                    Seja notificado sobre os resultados das suas apostas.
                                </small>
                            </div>
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" id="push_bet_reminder" 
                                       name="push_bet_reminder" {{ auth()->user()->push_bet_reminder ? 'checked' : '' }}>
                                <label class="form-check-label" for="push_bet_reminder">
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
                                <input type="checkbox" class="form-check-input" id="push_group_invite" 
                                       name="push_group_invite" {{ auth()->user()->push_group_invite ? 'checked' : '' }}>
                                <label class="form-check-label" for="push_group_invite">
                                    Convites para grupos
                                </label>
                                <small class="text-muted d-block mt-1">
                                    Seja notificado quando receber um convite.
                                </small>
                            </div>
                            <div class="form-check mb-2">
                                <input type="checkbox" class="form-check-input" id="push_group_activity" 
                                       name="push_group_activity" {{ auth()->user()->push_group_activity ? 'checked' : '' }}>
                                <label class="form-check-label" for="push_group_activity">
                                    Atividades do grupo
                                </label>
                                <small class="text-muted d-block mt-1">
                                    Receba atualizações sobre atividades no grupo.
                                </small>
                            </div>
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" id="push_group_message" 
                                       name="push_group_message" {{ auth()->user()->push_group_message ? 'checked' : '' }}>
                                <label class="form-check-label" for="push_group_message">
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
                                <input type="checkbox" class="form-check-input" id="push_promotions" 
                                       name="push_promotions" {{ auth()->user()->push_promotions ? 'checked' : '' }}>
                                <label class="form-check-label" for="push_promotions">
                                    Promoções e ofertas
                                </label>
                                <small class="text-muted d-block mt-1">
                                    Receba notificações sobre promoções especiais.
                                </small>
                            </div>
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" id="push_newsletter" 
                                       name="push_newsletter" {{ auth()->user()->push_newsletter ? 'checked' : '' }}>
                                <label class="form-check-label" for="push_newsletter">
                                    Newsletter
                                </label>
                                <small class="text-muted d-block mt-1">
                                    Receba notificações sobre novidades e dicas.
                                </small>
                            </div>
                        </div>

                        <!-- Notification Settings -->
                        <div class="mb-4">
                            <h5 class="mb-3">
                                <i class="fas fa-cog me-2"></i> Configurações
                            </h5>
                            <div class="form-check mb-2">
                                <input type="checkbox" class="form-check-input" id="push_sound" 
                                       name="push_sound" {{ auth()->user()->push_sound ? 'checked' : '' }}>
                                <label class="form-check-label" for="push_sound">
                                    Som nas notificações
                                </label>
                                <small class="text-muted d-block mt-1">
                                    Ative o som para as notificações push.
                                </small>
                            </div>
                            <div class="form-check mb-2">
                                <input type="checkbox" class="form-check-input" id="push_vibration" 
                                       name="push_vibration" {{ auth()->user()->push_vibration ? 'checked' : '' }}>
                                <label class="form-check-label" for="push_vibration">
                                    Vibração nas notificações
                                </label>
                                <small class="text-muted d-block mt-1">
                                    Ative a vibração para as notificações push.
                                </small>
                            </div>
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" id="push_badge" 
                                       name="push_badge" {{ auth()->user()->push_badge ? 'checked' : '' }}>
                                <label class="form-check-label" for="push_badge">
                                    Badge no ícone
                                </label>
                                <small class="text-muted d-block mt-1">
                                    Mostre o número de notificações no ícone do app.
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

            <!-- Push Tips -->
            <div class="card shadow-sm border-0 mt-4">
                <div class="card-body p-4">
                    <h5 class="card-title mb-3">
                        <i class="fas fa-info-circle me-2"></i> Dicas
                    </h5>
                    <ul class="list-unstyled text-muted mb-0">
                        <li class="mb-2">
                            <i class="fas fa-check-circle me-2"></i> Mantenha as notificações importantes ativas
                        </li>
                        <li class="mb-2">
                            <i class="fas fa-check-circle me-2"></i> Configure o som e vibração conforme sua preferência
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