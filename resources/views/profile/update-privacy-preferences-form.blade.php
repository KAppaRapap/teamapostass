@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card shadow-sm border-0">
                <div class="card-body p-4 p-md-5">
                    <div class="text-center mb-4">
                        <i class="fas fa-shield-alt fa-3x text-primary mb-3"></i>
                        <h1 class="h3">Configurações de Privacidade</h1>
                        <p class="text-muted">
                            Controle quem pode ver suas informações.
                        </p>
                    </div>

                    <form method="POST" action="{{ route('user.privacy-preferences.update') }}">
                        @csrf
                        @method('PUT')

                        <!-- Profile Visibility -->
                        <div class="mb-4">
                            <h5 class="mb-3">
                                <i class="fas fa-user me-2"></i> Visibilidade do Perfil
                            </h5>
                            <div class="form-check mb-2">
                                <input type="checkbox" class="form-check-input" id="show_balance" 
                                       name="show_balance" {{ auth()->user()->show_balance ? 'checked' : '' }}>
                                <label class="form-check-label" for="show_balance">
                                    Mostrar meu saldo
                                </label>
                            </div>
                            <div class="form-check mb-2">
                                <input type="checkbox" class="form-check-input" id="show_bets" 
                                       name="show_bets" {{ auth()->user()->show_bets ? 'checked' : '' }}>
                                <label class="form-check-label" for="show_bets">
                                    Mostrar minhas apostas
                                </label>
                            </div>
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" id="show_groups" 
                                       name="show_groups" {{ auth()->user()->show_groups ? 'checked' : '' }}>
                                <label class="form-check-label" for="show_groups">
                                    Mostrar meus grupos
                                </label>
                            </div>
                        </div>

                        <!-- Activity Visibility -->
                        <div class="mb-4">
                            <h5 class="mb-3">
                                <i class="fas fa-history me-2"></i> Visibilidade de Atividades
                            </h5>
                            <div class="form-check mb-2">
                                <input type="checkbox" class="form-check-input" id="show_activity" 
                                       name="show_activity" {{ auth()->user()->show_activity ? 'checked' : '' }}>
                                <label class="form-check-label" for="show_activity">
                                    Mostrar minhas atividades
                                </label>
                            </div>
                            <div class="form-check mb-2">
                                <input type="checkbox" class="form-check-input" id="show_winnings" 
                                       name="show_winnings" {{ auth()->user()->show_winnings ? 'checked' : '' }}>
                                <label class="form-check-label" for="show_winnings">
                                    Mostrar meus ganhos
                                </label>
                            </div>
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" id="show_statistics" 
                                       name="show_statistics" {{ auth()->user()->show_statistics ? 'checked' : '' }}>
                                <label class="form-check-label" for="show_statistics">
                                    Mostrar minhas estatísticas
                                </label>
                            </div>
                        </div>

                        <!-- Search Visibility -->
                        <div class="mb-4">
                            <h5 class="mb-3">
                                <i class="fas fa-search me-2"></i> Visibilidade na Busca
                            </h5>
                            <div class="form-check mb-2">
                                <input type="checkbox" class="form-check-input" id="show_in_search" 
                                       name="show_in_search" {{ auth()->user()->show_in_search ? 'checked' : '' }}>
                                <label class="form-check-label" for="show_in_search">
                                    Aparecer nos resultados de busca
                                </label>
                            </div>
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" id="show_in_leaderboard" 
                                       name="show_in_leaderboard" {{ auth()->user()->show_in_leaderboard ? 'checked' : '' }}>
                                <label class="form-check-label" for="show_in_leaderboard">
                                    Aparecer no ranking
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

            <!-- Privacy Tips -->
            <div class="card shadow-sm border-0 mt-4">
                <div class="card-body p-4">
                    <h5 class="card-title mb-3">
                        <i class="fas fa-info-circle me-2"></i> Dicas de Privacidade
                    </h5>
                    <ul class="list-unstyled text-muted mb-0">
                        <li class="mb-2">
                            <i class="fas fa-check-circle me-2"></i> Mantenha suas informações pessoais protegidas
                        </li>
                        <li class="mb-2">
                            <i class="fas fa-check-circle me-2"></i> Escolha cuidadosamente o que compartilhar
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