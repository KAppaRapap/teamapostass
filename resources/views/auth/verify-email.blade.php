@extends('layouts.guest')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-lg-5">
            <div class="card shadow-sm border-0">
                <div class="card-body p-4 p-md-5">
                    <div class="text-center mb-4">
                        <i class="fas fa-envelope-open-text fa-3x text-primary mb-3"></i>
                        <h1 class="h3">Verifique o seu E-mail</h1>
                        <p class="text-muted">
                            Enviámos um link de verificação para o seu e-mail.
                            Por favor, verifique a sua caixa de entrada.
                        </p>
                    </div>

                    <!-- Session Status -->
                    <x-auth-session-status class="mb-4" :status="session('status')" />

                    @if (session('status') == 'verification-link-sent')
                        <div class="alert alert-success">
                            <i class="fas fa-check-circle me-2"></i>
                            Um novo link de verificação foi enviado para o seu e-mail.
                        </div>
                    @endif

                    <form method="POST" action="{{ route('verification.send') }}">
                        @csrf

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-paper-plane me-2"></i> Reenviar E-mail
                            </button>

                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="btn btn-outline-secondary">
                                    <i class="fas fa-sign-out-alt me-2"></i> Sair
                                </button>
                            </form>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Help Card -->
            <div class="card shadow-sm border-0 mt-4">
                <div class="card-body p-4">
                    <h5 class="card-title mb-3">
                        <i class="fas fa-question-circle me-2"></i> Não recebeu o e-mail?
                    </h5>
                    <ul class="list-unstyled text-muted mb-0">
                        <li class="mb-2">
                            <i class="fas fa-check-circle me-2"></i> Verifique a sua pasta de spam
                        </li>
                        <li class="mb-2">
                            <i class="fas fa-check-circle me-2"></i> Confirme se o e-mail está correto
                        </li>
                        <li>
                            <i class="fas fa-check-circle me-2"></i> Aguarde alguns minutos e tente novamente
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

.btn-outline-secondary {
    color: #6c757d;
    border-color: #6c757d;
}

.btn-outline-secondary:hover {
    background-color: #6c757d;
    border-color: #6c757d;
    color: #fff;
}

.list-unstyled li {
    font-size: 0.9rem;
}

.alert {
    border-radius: 8px;
    border: none;
}

.alert-success {
    background-color: #e8f5e9;
    color: #2e7d32;
}
</style>
@endsection 