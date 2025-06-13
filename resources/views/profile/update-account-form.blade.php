@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card shadow-sm border-0">
                <div class="card-body p-4 p-md-5">
                    <div class="text-center mb-4">
                        <i class="fas fa-user-cog fa-3x text-primary mb-3"></i>
                        <h1 class="h3">Configurações da Conta</h1>
                        <p class="text-muted">
                            Gerencie suas informações pessoais.
                        </p>
                    </div>

                    <form method="POST" action="{{ route('user.account.update') }}">
                        @csrf
                        @method('PUT')

                        <!-- Profile Picture -->
                        <div class="mb-4">
                            <h5 class="mb-3">
                                <i class="fas fa-camera me-2"></i> Foto de Perfil
                            </h5>
                            <div class="d-flex align-items-center mb-3">
                                <div class="position-relative me-3">
                                    <img src="{{ auth()->user()->profile_photo_url }}" 
                                         alt="{{ auth()->user()->name }}" 
                                         class="rounded-circle" 
                                         style="width: 100px; height: 100px; object-fit: cover;">
                                    <button type="button" class="btn btn-sm btn-primary position-absolute bottom-0 end-0 rounded-circle" 
                                            style="width: 32px; height: 32px; padding: 0;">
                                        <i class="fas fa-camera"></i>
                                    </button>
                                </div>
                                <div>
                                    <p class="mb-1">Altere sua foto de perfil</p>
                                    <small class="text-muted">Formatos aceitos: JPG, PNG (máx. 2MB)</small>
                                </div>
                            </div>
                        </div>

                        <!-- Personal Information -->
                        <div class="mb-4">
                            <h5 class="mb-3">
                                <i class="fas fa-user me-2"></i> Informações Pessoais
                            </h5>
                            <div class="mb-3">
                                <label for="name" class="form-label">Nome</label>
                                <input type="text" class="form-control" id="name" name="name" 
                                       value="{{ auth()->user()->name }}" required>
                            </div>
                            <div class="mb-3">
                                <label for="email" class="form-label">E-mail</label>
                                <input type="email" class="form-control" id="email" name="email" 
                                       value="{{ auth()->user()->email }}" required>
                            </div>
                            <div class="mb-3">
                                <label for="phone" class="form-label">Telefone</label>
                                <input type="tel" class="form-control" id="phone" name="phone" 
                                       value="{{ auth()->user()->phone }}">
                            </div>
                        </div>

                        <!-- Social Media -->
                        <div class="mb-4">
                            <h5 class="mb-3">
                                <i class="fas fa-share-alt me-2"></i> Redes Sociais
                            </h5>
                            <div class="mb-3">
                                <label for="facebook" class="form-label">
                                    <i class="fab fa-facebook me-2"></i>Facebook
                                </label>
                                <input type="url" class="form-control" id="facebook" name="facebook" 
                                       value="{{ auth()->user()->facebook }}">
                            </div>
                            <div class="mb-3">
                                <label for="instagram" class="form-label">
                                    <i class="fab fa-instagram me-2"></i>Instagram
                                </label>
                                <input type="url" class="form-control" id="instagram" name="instagram" 
                                       value="{{ auth()->user()->instagram }}">
                            </div>
                            <div class="mb-3">
                                <label for="twitter" class="form-label">
                                    <i class="fab fa-twitter me-2"></i>Twitter
                                </label>
                                <input type="url" class="form-control" id="twitter" name="twitter" 
                                       value="{{ auth()->user()->twitter }}">
                            </div>
                        </div>

                        <!-- Bio -->
                        <div class="mb-4">
                            <h5 class="mb-3">
                                <i class="fas fa-quote-left me-2"></i> Biografia
                            </h5>
                            <div class="mb-3">
                                <textarea class="form-control" id="bio" name="bio" rows="4" 
                                          placeholder="Conte um pouco sobre você...">{{ auth()->user()->bio }}</textarea>
                                <small class="text-muted d-block mt-2">
                                    Máximo de 500 caracteres.
                                </small>
                            </div>
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-2"></i> Salvar Alterações
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Account Tips -->
            <div class="card shadow-sm border-0 mt-4">
                <div class="card-body p-4">
                    <h5 class="card-title mb-3">
                        <i class="fas fa-info-circle me-2"></i> Dicas
                    </h5>
                    <ul class="list-unstyled text-muted mb-0">
                        <li class="mb-2">
                            <i class="fas fa-check-circle me-2"></i> Mantenha suas informações atualizadas
                        </li>
                        <li class="mb-2">
                            <i class="fas fa-check-circle me-2"></i> Use uma foto de perfil clara e profissional
                        </li>
                        <li>
                            <i class="fas fa-check-circle me-2"></i> Adicione suas redes sociais para conectar com outros usuários
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

.rounded-circle {
    border: 2px solid #fff;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}
</style>
@endsection 