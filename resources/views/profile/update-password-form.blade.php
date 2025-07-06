@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card shadow-sm border-0">
                <div class="card-body p-4 p-md-5">
                    <div class="text-center mb-4">
                        <i class="fas fa-key fa-3x text-primary mb-3"></i>
                        <h1 class="h3">Atualizar Senha</h1>
                        <p class="text-muted">
                            Mantenha sua conta segura usando uma palavra-passe forte.
                        </p>
                    </div>

                    <form method="POST" action="{{ route('user-password.update') }}">
                        @csrf
                        @method('PUT')

                        <!-- Current Password -->
                        <div class="mb-3">
                            <label for="current_password" class="form-label">Senha Atual</label>
                            <input type="password" class="form-control @error('current_password') is-invalid @enderror" 
                                   id="current_password" name="current_password" required>
                            @error('current_password')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- New Password -->
                        <div class="mb-3">
                            <label for="password" class="form-label">Nova Senha</label>
                            <input type="password" class="form-control @error('password') is-invalid @enderror" 
                                   id="password" name="password" required>
                            @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">
                                A palavra-passe deve ter pelo menos 8 caracteres, incluindo letras e números.
                            </div>
                        </div>

                        <!-- Confirm Password -->
                        <div class="mb-4">
                            <label for="password_confirmation" class="form-label">Confirmar Nova Senha</label>
                            <input type="password" class="form-control" 
                                   id="password_confirmation" name="password_confirmation" required>
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-2"></i> Atualizar Senha
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Password Tips -->
            <div class="card shadow-sm border-0 mt-4">
                <div class="card-body p-4">
                    <h5 class="card-title mb-3">
                        <i class="fas fa-shield-alt me-2"></i> Dicas para uma Senha Forte
                    </h5>
                    <ul class="list-unstyled text-muted mb-0">
                        <li class="mb-2">
                            <i class="fas fa-check-circle me-2"></i> Use pelo menos 8 caracteres
                        </li>
                        <li class="mb-2">
                            <i class="fas fa-check-circle me-2"></i> Combine letras maiúsculas e minúsculas
                        </li>
                        <li class="mb-2">
                            <i class="fas fa-check-circle me-2"></i> Inclua números e caracteres especiais
                        </li>
                        <li>
                            <i class="fas fa-check-circle me-2"></i> Evite usar informações pessoais
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

.list-unstyled li {
    font-size: 0.9rem;
}
</style>
@endsection 