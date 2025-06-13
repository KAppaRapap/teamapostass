@extends('layouts.guest')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-lg-5">
            <div class="card shadow-sm border-0">
                <div class="card-body p-4 p-md-5">
                    <div class="text-center mb-4">
                        <i class="fas fa-lock fa-3x text-primary mb-3"></i>
                        <h1 class="h3">Autenticação em Duas Etapas</h1>
                        <p class="text-muted">
                            Por favor, insira o código de autenticação ou use um código de recuperação.
                        </p>
                    </div>

                    <!-- Session Status -->
                    <x-auth-session-status class="mb-4" :status="session('status')" />

                    <ul class="nav nav-pills nav-justified mb-4" id="authTabs" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="code-tab" data-bs-toggle="tab" 
                                    data-bs-target="#code" type="button" role="tab">
                                <i class="fas fa-key me-2"></i> Código
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="recovery-tab" data-bs-toggle="tab" 
                                    data-bs-target="#recovery" type="button" role="tab">
                                <i class="fas fa-redo me-2"></i> Recuperação
                            </button>
                        </li>
                    </ul>

                    <div class="tab-content" id="authTabsContent">
                        <!-- Authentication Code -->
                        <div class="tab-pane fade show active" id="code" role="tabpanel">
                            <form method="POST" action="{{ route('two-factor.login') }}">
                                @csrf

                                <div class="mb-4">
                                    <label for="code" class="form-label">Código de Autenticação</label>
                                    <input type="text" class="form-control @error('code') is-invalid @enderror" 
                                           id="code" name="code" required autofocus>
                                    @error('code')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="d-grid">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-check-circle me-2"></i> Verificar
                                    </button>
                                </div>
                            </form>
                        </div>

                        <!-- Recovery Code -->
                        <div class="tab-pane fade" id="recovery" role="tabpanel">
                            <form method="POST" action="{{ route('two-factor.login') }}">
                                @csrf

                                <div class="mb-4">
                                    <label for="recovery_code" class="form-label">Código de Recuperação</label>
                                    <input type="text" class="form-control @error('recovery_code') is-invalid @enderror" 
                                           id="recovery_code" name="recovery_code" required>
                                    @error('recovery_code')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="d-grid">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-check-circle me-2"></i> Verificar
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Help Card -->
            <div class="card shadow-sm border-0 mt-4">
                <div class="card-body p-4">
                    <h5 class="card-title mb-3">
                        <i class="fas fa-info-circle me-2"></i> Precisa de ajuda?
                    </h5>
                    <ul class="list-unstyled text-muted mb-0">
                        <li class="mb-2">
                            <i class="fas fa-check-circle me-2"></i> Use o aplicativo autenticador
                        </li>
                        <li class="mb-2">
                            <i class="fas fa-check-circle me-2"></i> Verifique se o código está correto
                        </li>
                        <li>
                            <i class="fas fa-check-circle me-2"></i> Use um código de recuperação se necessário
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

.nav-pills .nav-link {
    border-radius: 8px;
    padding: 0.75rem 1rem;
    color: #6c757d;
}

.nav-pills .nav-link.active {
    background-color: var(--primary-color);
    color: #fff;
}

.list-unstyled li {
    font-size: 0.9rem;
}
</style>
@endsection 