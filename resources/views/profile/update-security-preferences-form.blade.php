@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card shadow-sm border-0">
                <div class="card-body p-4 p-md-5">
                    <div class="text-center mb-4">
                        <i class="fas fa-shield-alt fa-3x text-primary mb-3"></i>
                        <h1 class="h3">Notificações de Segurança</h1>
                        <p class="text-muted">
                            Configure suas notificações relacionadas à segurança.
                        </p>
                    </div>

                    <form method="POST" action="{{ route('user.security-preferences.update') }}">
                        @csrf
                        @method('PUT')

                        <!-- Login Notifications -->
                        <div class="mb-4">
                            <h5 class="mb-3">
                                <i class="fas fa-sign-in-alt me-2"></i> Notificações de Login
                            </h5>
                            <div class="form-check mb-2">
                                <input type="checkbox" class="form-check-input" id="login_success" 
                                       name="login_success" {{ auth()->user()->login_success ? 'checked' : '' }}>
                                <label class="form-check-label" for="login_success">
                                    Login bem-sucedido
                                </label>
                                <small class="text-muted d-block mt-1">
                                    Seja notificado sobre logins bem-sucedidos.
                                </small>
                            </div>
                            <div class="form-check mb-2">
                                <input type="checkbox" class="form-check-input" id="login_failed" 
                                       name="login_failed" {{ auth()->user()->login_failed ? 'checked' : '' }}>
                                <label class="form-check-label" for="login_failed">
                                    Tentativas falhas
                                </label>
                                <small class="text-muted d-block mt-1">
                                    Receba notificações sobre tentativas falhas de login.
                                </small>
                            </div>
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" id="login_new_device" 
                                       name="login_new_device" {{ auth()->user()->login_new_device ? 'checked' : '' }}>
                                <label class="form-check-label" for="login_new_device">
                                    Login em novo dispositivo
                                </label>
                                <small class="text-muted d-block mt-1">
                                    Seja notificado sobre logins em novos dispositivos.
                                </small>
                            </div>
                        </div>

                        <!-- Security Alerts -->
                        <div class="mb-4">
                            <h5 class="mb-3">
                                <i class="fas fa-exclamation-triangle me-2"></i> Alertas de Segurança
                            </h5>
                            <div class="form-check mb-2">
                                <input type="checkbox" class="form-check-input" id="alert_password" 
                                       name="alert_password" {{ auth()->user()->alert_password ? 'checked' : '' }}>
                                <label class="form-check-label" for="alert_password">
                                    Alteração de senha
                                </label>
                                <small class="text-muted d-block mt-1">
                                    Receba notificações sobre alterações de senha.
                                </small>
                            </div>
                            <div class="form-check mb-2">
                                <input type="checkbox" class="form-check-input" id="alert_email" 
                                       name="alert_email" {{ auth()->user()->alert_email ? 'checked' : '' }}>
                                <label class="form-check-label" for="alert_email">
                                    Alteração de email
                                </label>
                                <small class="text-muted d-block mt-1">
                                    Seja notificado sobre alterações de email.
                                </small>
                            </div>
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" id="alert_phone" 
                                       name="alert_phone" {{ auth()->user()->alert_phone ? 'checked' : '' }}>
                                <label class="form-check-label" for="alert_phone">
                                    Alteração de telefone
                                </label>
                                <small class="text-muted d-block mt-1">
                                    Receba notificações sobre alterações de telefone.
                                </small>
                            </div>
                        </div>

                        <!-- Two-Factor Authentication -->
                        <div class="mb-4">
                            <h5 class="mb-3">
                                <i class="fas fa-lock me-2"></i> Autenticação de Dois Fatores
                            </h5>
                            <div class="form-check mb-2">
                                <input type="checkbox" class="form-check-input" id="2fa_enabled" 
                                       name="2fa_enabled" {{ auth()->user()->2fa_enabled ? 'checked' : '' }}>
                                <label class="form-check-label" for="2fa_enabled">
                                    2FA ativado
                                </label>
                                <small class="text-muted d-block mt-1">
                                    Seja notificado quando 2FA for ativado.
                                </small>
                            </div>
                            <div class="form-check mb-2">
                                <input type="checkbox" class="form-check-input" id="2fa_disabled" 
                                       name="2fa_disabled" {{ auth()->user()->2fa_disabled ? 'checked' : '' }}>
                                <label class="form-check-label" for="2fa_disabled">
                                    2FA desativado
                                </label>
                                <small class="text-muted d-block mt-1">
                                    Receba notificações quando 2FA for desativado.
                                </small>
                            </div>
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" id="2fa_backup" 
                                       name="2fa_backup" {{ auth()->user()->2fa_backup ? 'checked' : '' }}>
                                <label class="form-check-label" for="2fa_backup">
                                    Códigos de backup
                                </label>
                                <small class="text-muted d-block mt-1">
                                    Seja notificado sobre códigos de backup.
                                </small>
                            </div>
                        </div>

                        <!-- Account Activity -->
                        <div class="mb-4">
                            <h5 class="mb-3">
                                <i class="fas fa-user-shield me-2"></i> Atividade da Conta
                            </h5>
                            <div class="form-check mb-2">
                                <input type="checkbox" class="form-check-input" id="activity_suspicious" 
                                       name="activity_suspicious" {{ auth()->user()->activity_suspicious ? 'checked' : '' }}>
                                <label class="form-check-label" for="activity_suspicious">
                                    Atividades suspeitas
                                </label>
                                <small class="text-muted d-block mt-1">
                                    Receba notificações sobre atividades suspeitas.
                                </small>
                            </div>
                            <div class="form-check mb-2">
                                <input type="checkbox" class="form-check-input" id="activity_location" 
                                       name="activity_location" {{ auth()->user()->activity_location ? 'checked' : '' }}>
                                <label class="form-check-label" for="activity_location">
                                    Acesso de nova localização
                                </label>
                                <small class="text-muted d-block mt-1">
                                    Seja notificado sobre acessos de novas localizações.
                                </small>
                            </div>
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" id="activity_ip" 
                                       name="activity_ip" {{ auth()->user()->activity_ip ? 'checked' : '' }}>
                                <label class="form-check-label" for="activity_ip">
                                    Alteração de IP
                                </label>
                                <small class="text-muted d-block mt-1">
                                    Receba notificações sobre alterações de IP.
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

            <!-- Security Tips -->
            <div class="card shadow-sm border-0 mt-4">
                <div class="card-body p-4">
                    <h5 class="card-title mb-3">
                        <i class="fas fa-info-circle me-2"></i> Dicas
                    </h5>
                    <ul class="list-unstyled text-muted mb-0">
                        <li class="mb-2">
                            <i class="fas fa-check-circle me-2"></i> Mantenha-se informado sobre a segurança da sua conta
                        </li>
                        <li class="mb-2">
                            <i class="fas fa-check-circle me-2"></i> Configure alertas para atividades suspeitas
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