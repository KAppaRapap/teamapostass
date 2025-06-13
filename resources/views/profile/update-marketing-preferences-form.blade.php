@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card shadow-sm border-0">
                <div class="card-body p-4 p-md-5">
                    <div class="text-center mb-4">
                        <i class="fas fa-bullhorn fa-3x text-primary mb-3"></i>
                        <h1 class="h3">Notificações de Marketing</h1>
                        <p class="text-muted">
                            Configure suas notificações relacionadas a marketing.
                        </p>
                    </div>

                    <form method="POST" action="{{ route('user.marketing-preferences.update') }}">
                        @csrf
                        @method('PUT')

                        <!-- Promotions -->
                        <div class="mb-4">
                            <h5 class="mb-3">
                                <i class="fas fa-tag me-2"></i> Promoções
                            </h5>
                            <div class="form-check mb-2">
                                <input type="checkbox" class="form-check-input" id="promo_special" 
                                       name="promo_special" {{ auth()->user()->promo_special ? 'checked' : '' }}>
                                <label class="form-check-label" for="promo_special">
                                    Ofertas especiais
                                </label>
                                <small class="text-muted d-block mt-1">
                                    Receba notificações sobre ofertas especiais.
                                </small>
                            </div>
                            <div class="form-check mb-2">
                                <input type="checkbox" class="form-check-input" id="promo_discount" 
                                       name="promo_discount" {{ auth()->user()->promo_discount ? 'checked' : '' }}>
                                <label class="form-check-label" for="promo_discount">
                                    Descontos
                                </label>
                                <small class="text-muted d-block mt-1">
                                    Seja notificado sobre descontos disponíveis.
                                </small>
                            </div>
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" id="promo_bonus" 
                                       name="promo_bonus" {{ auth()->user()->promo_bonus ? 'checked' : '' }}>
                                <label class="form-check-label" for="promo_bonus">
                                    Bônus
                                </label>
                                <small class="text-muted d-block mt-1">
                                    Receba notificações sobre bônus disponíveis.
                                </small>
                            </div>
                        </div>

                        <!-- Newsletters -->
                        <div class="mb-4">
                            <h5 class="mb-3">
                                <i class="fas fa-newspaper me-2"></i> Newsletters
                            </h5>
                            <div class="form-check mb-2">
                                <input type="checkbox" class="form-check-input" id="news_weekly" 
                                       name="news_weekly" {{ auth()->user()->news_weekly ? 'checked' : '' }}>
                                <label class="form-check-label" for="news_weekly">
                                    Newsletter semanal
                                </label>
                                <small class="text-muted d-block mt-1">
                                    Receba a newsletter semanal com novidades.
                                </small>
                            </div>
                            <div class="form-check mb-2">
                                <input type="checkbox" class="form-check-input" id="news_monthly" 
                                       name="news_monthly" {{ auth()->user()->news_monthly ? 'checked' : '' }}>
                                <label class="form-check-label" for="news_monthly">
                                    Newsletter mensal
                                </label>
                                <small class="text-muted d-block mt-1">
                                    Seja notificado sobre a newsletter mensal.
                                </small>
                            </div>
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" id="news_special" 
                                       name="news_special" {{ auth()->user()->news_special ? 'checked' : '' }}>
                                <label class="form-check-label" for="news_special">
                                    Notícias especiais
                                </label>
                                <small class="text-muted d-block mt-1">
                                    Receba notificações sobre notícias especiais.
                                </small>
                            </div>
                        </div>

                        <!-- Events -->
                        <div class="mb-4">
                            <h5 class="mb-3">
                                <i class="fas fa-calendar me-2"></i> Eventos
                            </h5>
                            <div class="form-check mb-2">
                                <input type="checkbox" class="form-check-input" id="event_upcoming" 
                                       name="event_upcoming" {{ auth()->user()->event_upcoming ? 'checked' : '' }}>
                                <label class="form-check-label" for="event_upcoming">
                                    Eventos futuros
                                </label>
                                <small class="text-muted d-block mt-1">
                                    Seja notificado sobre eventos futuros.
                                </small>
                            </div>
                            <div class="form-check mb-2">
                                <input type="checkbox" class="form-check-input" id="event_reminder" 
                                       name="event_reminder" {{ auth()->user()->event_reminder ? 'checked' : '' }}>
                                <label class="form-check-label" for="event_reminder">
                                    Lembretes de eventos
                                </label>
                                <small class="text-muted d-block mt-1">
                                    Receba lembretes sobre eventos próximos.
                                </small>
                            </div>
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" id="event_special" 
                                       name="event_special" {{ auth()->user()->event_special ? 'checked' : '' }}>
                                <label class="form-check-label" for="event_special">
                                    Eventos especiais
                                </label>
                                <small class="text-muted d-block mt-1">
                                    Seja notificado sobre eventos especiais.
                                </small>
                            </div>
                        </div>

                        <!-- Surveys -->
                        <div class="mb-4">
                            <h5 class="mb-3">
                                <i class="fas fa-poll me-2"></i> Pesquisas
                            </h5>
                            <div class="form-check mb-2">
                                <input type="checkbox" class="form-check-input" id="survey_general" 
                                       name="survey_general" {{ auth()->user()->survey_general ? 'checked' : '' }}>
                                <label class="form-check-label" for="survey_general">
                                    Pesquisas gerais
                                </label>
                                <small class="text-muted d-block mt-1">
                                    Receba notificações sobre pesquisas gerais.
                                </small>
                            </div>
                            <div class="form-check mb-2">
                                <input type="checkbox" class="form-check-input" id="survey_product" 
                                       name="survey_product" {{ auth()->user()->survey_product ? 'checked' : '' }}>
                                <label class="form-check-label" for="survey_product">
                                    Pesquisas de produtos
                                </label>
                                <small class="text-muted d-block mt-1">
                                    Seja notificado sobre pesquisas de produtos.
                                </small>
                            </div>
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" id="survey_service" 
                                       name="survey_service" {{ auth()->user()->survey_service ? 'checked' : '' }}>
                                <label class="form-check-label" for="survey_service">
                                    Pesquisas de serviços
                                </label>
                                <small class="text-muted d-block mt-1">
                                    Receba notificações sobre pesquisas de serviços.
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

            <!-- Marketing Tips -->
            <div class="card shadow-sm border-0 mt-4">
                <div class="card-body p-4">
                    <h5 class="card-title mb-3">
                        <i class="fas fa-info-circle me-2"></i> Dicas
                    </h5>
                    <ul class="list-unstyled text-muted mb-0">
                        <li class="mb-2">
                            <i class="fas fa-check-circle me-2"></i> Mantenha-se informado sobre promoções e ofertas
                        </li>
                        <li class="mb-2">
                            <i class="fas fa-check-circle me-2"></i> Configure notificações para eventos importantes
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