@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card shadow-sm border-0">
                <div class="card-body p-4 p-md-5">
                    <div class="text-center mb-4">
                        <i class="fas fa-cog fa-3x text-primary mb-3"></i>
                        <h1 class="h3">Preferências</h1>
                        <p class="text-muted">
                            Personalize sua experiência.
                        </p>
                    </div>

                    <form method="POST" action="{{ route('user.preferences.update') }}">
                        @csrf
                        @method('PUT')

                        <!-- Language -->
                        <div class="mb-4">
                            <h5 class="mb-3">
                                <i class="fas fa-language me-2"></i> Idioma
                            </h5>
                            <select class="form-select" id="language" name="language">
                                <option value="pt-PT" {{ auth()->user()->language === 'pt-PT' ? 'selected' : '' }}>Português (Portugal)</option>
                                <option value="en" {{ auth()->user()->language === 'en' ? 'selected' : '' }}>English</option>
                                <option value="es" {{ auth()->user()->language === 'es' ? 'selected' : '' }}>Español</option>
                            </select>
                            <small class="text-muted d-block mt-2">
                                Escolha o idioma da interface.
                            </small>
                        </div>

                        <!-- Timezone -->
                        <div class="mb-4">
                            <h5 class="mb-3">
                                <i class="fas fa-globe me-2"></i> Fuso Horário
                            </h5>
                            <select class="form-select" id="timezone" name="timezone">
                                <option value="Europe/Lisbon" {{ auth()->user()->timezone === 'Europe/Lisbon' ? 'selected' : '' }}>Lisboa (GMT+0/+1)</option>
                                <option value="Europe/Madrid" {{ auth()->user()->timezone === 'Europe/Madrid' ? 'selected' : '' }}>Madrid (GMT+1)</option>
                                <option value="Europe/London" {{ auth()->user()->timezone === 'Europe/London' ? 'selected' : '' }}>Londres (GMT+0/+1)</option>
                                <option value="Europe/Paris" {{ auth()->user()->timezone === 'Europe/Paris' ? 'selected' : '' }}>Paris (GMT+1/+2)</option>
                            </select>
                            <small class="text-muted d-block mt-2">
                                Selecione o seu fuso horário local.
                            </small>
                        </div>

                        <!-- Currency -->
                        <div class="mb-4">
                            <h5 class="mb-3">
                                <i class="fas fa-money-bill me-2"></i> Moeda
                            </h5>
                            <select class="form-select" id="currency" name="currency">
                                <option value="EUR" {{ auth()->user()->currency === 'EUR' ? 'selected' : '' }}>Euro (€)</option>
                                <option value="USD" {{ auth()->user()->currency === 'USD' ? 'selected' : '' }}>Dólar ($)</option>
                                <option value="GBP" {{ auth()->user()->currency === 'GBP' ? 'selected' : '' }}>Libra (£)</option>
                            </select>
                            <small class="text-muted d-block mt-2">
                                Escolha a moeda para exibição de valores.
                            </small>
                        </div>

                        <!-- Theme -->
                        <div class="mb-4">
                            <h5 class="mb-3">
                                <i class="fas fa-palette me-2"></i> Tema
                            </h5>
                            <div class="form-check mb-2">
                                <input type="radio" class="form-check-input" id="theme_light" 
                                       name="theme" value="light" {{ auth()->user()->theme === 'light' ? 'checked' : '' }}>
                                <label class="form-check-label" for="theme_light">
                                    <i class="fas fa-sun me-2"></i> Claro
                                </label>
                            </div>
                            <div class="form-check mb-2">
                                <input type="radio" class="form-check-input" id="theme_dark" 
                                       name="theme" value="dark" {{ auth()->user()->theme === 'dark' ? 'checked' : '' }}>
                                <label class="form-check-label" for="theme_dark">
                                    <i class="fas fa-moon me-2"></i> Escuro
                                </label>
                            </div>
                            <div class="form-check">
                                <input type="radio" class="form-check-input" id="theme_system" 
                                       name="theme" value="system" {{ auth()->user()->theme === 'system' ? 'checked' : '' }}>
                                <label class="form-check-label" for="theme_system">
                                    <i class="fas fa-desktop me-2"></i> Sistema
                                </label>
                            </div>
                            <small class="text-muted d-block mt-2">
                                Escolha o tema da interface.
                            </small>
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-2"></i> Salvar Preferências
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Preferences Tips -->
            <div class="card shadow-sm border-0 mt-4">
                <div class="card-body p-4">
                    <h5 class="card-title mb-3">
                        <i class="fas fa-info-circle me-2"></i> Dicas
                    </h5>
                    <ul class="list-unstyled text-muted mb-0">
                        <li class="mb-2">
                            <i class="fas fa-check-circle me-2"></i> Escolha o idioma que você prefere
                        </li>
                        <li class="mb-2">
                            <i class="fas fa-check-circle me-2"></i> Configure o fuso horário correto
                        </li>
                        <li>
                            <i class="fas fa-check-circle me-2"></i> Personalize o tema conforme sua preferência
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