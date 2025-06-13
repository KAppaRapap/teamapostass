@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card shadow-sm border-0">
                <div class="card-body p-4 p-md-5">
                    <div class="text-center mb-4">
                        <i class="fas fa-dice fa-3x text-primary mb-3"></i>
                        <h1 class="h3">Notificações de Apostas</h1>
                        <p class="text-muted">
                            Configure suas notificações relacionadas a apostas.
                        </p>
                    </div>

                    <form method="POST" action="{{ route('user.betting-preferences.update') }}">
                        @csrf
                        @method('PUT')

                        <!-- Bet Notifications -->
                        <div class="mb-4">
                            <h5 class="mb-3">
                                <i class="fas fa-bell me-2"></i> Notificações de Apostas
                            </h5>
                            <div class="form-check mb-2">
                                <input type="checkbox" class="form-check-input" id="bet_placed" 
                                       name="bet_placed" {{ auth()->user()->bet_placed ? 'checked' : '' }}>
                                <label class="form-check-label" for="bet_placed">
                                    Aposta realizada
                                </label>
                                <small class="text-muted d-block mt-1">
                                    Receba notificações quando uma aposta for realizada.
                                </small>
                            </div>
                            <div class="form-check mb-2">
                                <input type="checkbox" class="form-check-input" id="bet_result" 
                                       name="bet_result" {{ auth()->user()->bet_result ? 'checked' : '' }}>
                                <label class="form-check-label" for="bet_result">
                                    Resultado da aposta
                                </label>
                                <small class="text-muted d-block mt-1">
                                    Seja notificado sobre resultados de apostas.
                                </small>
                            </div>
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" id="bet_reminder" 
                                       name="bet_reminder" {{ auth()->user()->bet_reminder ? 'checked' : '' }}>
                                <label class="form-check-label" for="bet_reminder">
                                    Lembretes de apostas
                                </label>
                                <small class="text-muted d-block mt-1">
                                    Receba lembretes sobre apostas pendentes.
                                </small>
                            </div>
                        </div>

                        <!-- Bet Alerts -->
                        <div class="mb-4">
                            <h5 class="mb-3">
                                <i class="fas fa-exclamation-circle me-2"></i> Alertas de Apostas
                            </h5>
                            <div class="form-check mb-2">
                                <input type="checkbox" class="form-check-input" id="alert_limit" 
                                       name="alert_limit" {{ auth()->user()->alert_limit ? 'checked' : '' }}>
                                <label class="form-check-label" for="alert_limit">
                                    Limites de apostas
                                </label>
                                <small class="text-muted d-block mt-1">
                                    Seja notificado sobre limites de apostas.
                                </small>
                            </div>
                            <div class="form-check mb-2">
                                <input type="checkbox" class="form-check-input" id="alert_risk" 
                                       name="alert_risk" {{ auth()->user()->alert_risk ? 'checked' : '' }}>
                                <label class="form-check-label" for="alert_risk">
                                    Alertas de risco
                                </label>
                                <small class="text-muted d-block mt-1">
                                    Receba notificações sobre alertas de risco.
                                </small>
                            </div>
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" id="alert_suspicious" 
                                       name="alert_suspicious" {{ auth()->user()->alert_suspicious ? 'checked' : '' }}>
                                <label class="form-check-label" for="alert_suspicious">
                                    Atividades suspeitas
                                </label>
                                <small class="text-muted d-block mt-1">
                                    Seja notificado sobre atividades suspeitas.
                                </small>
                            </div>
                        </div>

                        <!-- Bet Updates -->
                        <div class="mb-4">
                            <h5 class="mb-3">
                                <i class="fas fa-sync me-2"></i> Atualizações de Apostas
                            </h5>
                            <div class="form-check mb-2">
                                <input type="checkbox" class="form-check-input" id="update_odds" 
                                       name="update_odds" {{ auth()->user()->update_odds ? 'checked' : '' }}>
                                <label class="form-check-label" for="update_odds">
                                    Mudanças nas odds
                                </label>
                                <small class="text-muted d-block mt-1">
                                    Receba notificações sobre mudanças nas odds.
                                </small>
                            </div>
                            <div class="form-check mb-2">
                                <input type="checkbox" class="form-check-input" id="update_status" 
                                       name="update_status" {{ auth()->user()->update_status ? 'checked' : '' }}>
                                <label class="form-check-label" for="update_status">
                                    Status da aposta
                                </label>
                                <small class="text-muted d-block mt-1">
                                    Seja notificado sobre status de apostas.
                                </small>
                            </div>
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" id="update_canceled" 
                                       name="update_canceled" {{ auth()->user()->update_canceled ? 'checked' : '' }}>
                                <label class="form-check-label" for="update_canceled">
                                    Apostas canceladas
                                </label>
                                <small class="text-muted d-block mt-1">
                                    Receba notificações sobre apostas canceladas.
                                </small>
                            </div>
                        </div>

                        <!-- Bet Wins -->
                        <div class="mb-4">
                            <h5 class="mb-3">
                                <i class="fas fa-trophy me-2"></i> Vitórias em Apostas
                            </h5>
                            <div class="form-check mb-2">
                                <input type="checkbox" class="form-check-input" id="win_small" 
                                       name="win_small" {{ auth()->user()->win_small ? 'checked' : '' }}>
                                <label class="form-check-label" for="win_small">
                                    Vitórias pequenas
                                </label>
                                <small class="text-muted d-block mt-1">
                                    Seja notificado sobre vitórias pequenas.
                                </small>
                            </div>
                            <div class="form-check mb-2">
                                <input type="checkbox" class="form-check-input" id="win_medium" 
                                       name="win_medium" {{ auth()->user()->win_medium ? 'checked' : '' }}>
                                <label class="form-check-label" for="win_medium">
                                    Vitórias médias
                                </label>
                                <small class="text-muted d-block mt-1">
                                    Receba notificações sobre vitórias médias.
                                </small>
                            </div>
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" id="win_large" 
                                       name="win_large" {{ auth()->user()->win_large ? 'checked' : '' }}>
                                <label class="form-check-label" for="win_large">
                                    Vitórias grandes
                                </label>
                                <small class="text-muted d-block mt-1">
                                    Seja notificado sobre vitórias grandes.
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

            <!-- Betting Tips -->
            <div class="card shadow-sm border-0 mt-4">
                <div class="card-body p-4">
                    <h5 class="card-title mb-3">
                        <i class="fas fa-info-circle me-2"></i> Dicas
                    </h5>
                    <ul class="list-unstyled text-muted mb-0">
                        <li class="mb-2">
                            <i class="fas fa-check-circle me-2"></i> Mantenha-se informado sobre suas apostas
                        </li>
                        <li class="mb-2">
                            <i class="fas fa-check-circle me-2"></i> Configure alertas para apostas importantes
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