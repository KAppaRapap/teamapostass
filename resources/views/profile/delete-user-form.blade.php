@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card shadow-sm border-0">
                <div class="card-body p-4 p-md-5">
                    <div class="text-center mb-4">
                        <i class="fas fa-user-times fa-3x text-danger mb-3"></i>
                        <h1 class="h3">Excluir Conta</h1>
                        <p class="text-muted">
                            Esta ação é permanente e não pode ser desfeita.
                        </p>
                    </div>

                    <div class="alert alert-warning">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        <strong>Atenção!</strong> Ao excluir sua conta:
                        <ul class="mb-0 mt-2">
                            <li>Todos os seus dados serão permanentemente removidos</li>
                            <li>Suas apostas e grupos serão excluídos</li>
                            <li>Seu saldo será perdido</li>
                            <li>Esta ação não pode ser desfeita</li>
                        </ul>
                    </div>

                    <form method="POST" action="{{ route('user.destroy') }}" class="mt-4">
                        @csrf
                        @method('DELETE')

                        <div class="mb-4">
                            <label for="password" class="form-label">Digite sua senha para confirmar</label>
                            <input type="password" class="form-control @error('password') is-invalid @enderror" 
                                   id="password" name="password" required>
                            @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input @error('confirm') is-invalid @enderror" 
                                       id="confirm" name="confirm" required>
                                <label class="form-check-label" for="confirm">
                                    Eu entendo que esta ação é permanente e não pode ser desfeita
                                </label>
                                @error('confirm')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-danger">
                                <i class="fas fa-trash-alt me-2"></i> Excluir Minha Conta
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Backup Data -->
            <div class="card shadow-sm border-0 mt-4">
                <div class="card-body p-4">
                    <h5 class="card-title mb-3">
                        <i class="fas fa-download me-2"></i> Fazer Backup dos Dados
                    </h5>
                    <p class="card-text text-muted mb-3">
                        Antes de excluir sua conta, você pode fazer o download de seus dados pessoais.
                    </p>
                    <form method="POST" action="{{ route('user.data-export') }}">
                        @csrf
                        <div class="d-grid">
                            <button type="submit" class="btn btn-outline-primary">
                                <i class="fas fa-file-download me-2"></i> Baixar Meus Dados
                            </button>
                        </div>
                    </form>
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

.btn-danger {
    background-color: #dc3545;
    border-color: #dc3545;
}

.btn-danger:hover {
    background-color: #bb2d3b;
    border-color: #bb2d3b;
}

.btn-outline-primary {
    color: var(--primary-color);
    border-color: var(--primary-color);
}

.btn-outline-primary:hover {
    background-color: var(--primary-color);
    border-color: var(--primary-color);
}

.alert {
    border-radius: 8px;
    border: none;
}

.alert-warning {
    background-color: #fff3cd;
    color: #856404;
}

.alert ul {
    padding-left: 1.5rem;
}

.alert li {
    font-size: 0.9rem;
}

.form-check-input:checked {
    background-color: var(--primary-color);
    border-color: var(--primary-color);
}

.form-check-input:focus {
    box-shadow: 0 0 0 0.2rem rgba(25, 118, 210, 0.15);
}
</style>
@endsection 