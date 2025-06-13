@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row mb-4">
        <div class="col-12">
            <h1 class="h3 mb-0">Configurações</h1>
            <p class="text-muted">Gerencie suas preferências e informações pessoais</p>
        </div>
    </div>

    <div class="row g-4">
        <!-- Perfil -->
        <div class="col-lg-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title mb-4">Perfil</h5>
                    <form action="{{ route('settings.update-profile') }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="mb-3">
                            <label for="name" class="form-label">Nome</label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $user->name) }}" required>
                            @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="email" class="form-label">E-mail</label>
                            <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email', $user->email) }}" required>
                            @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="phone" class="form-label">Telefone</label>
                            <input type="tel" class="form-control @error('phone') is-invalid @enderror" id="phone" name="phone" value="{{ old('phone', $user->phone) }}">
                            @error('phone')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-2"></i> Salvar Alterações
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Segurança -->
        <div class="col-lg-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title mb-4">Segurança</h5>
                    <form action="{{ route('settings.update-password') }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="mb-3">
                            <label for="current_password" class="form-label">Senha Atual</label>
                            <input type="password" class="form-control @error('current_password') is-invalid @enderror" id="current_password" name="current_password" required>
                            @error('current_password')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="password" class="form-label">Nova Senha</label>
                            <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password" required>
                            @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="password_confirmation" class="form-label">Confirmar Nova Senha</label>
                            <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" required>
                        </div>

                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-key me-2"></i> Alterar Senha
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Preferências -->
        <div class="col-lg-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title mb-4">Preferências</h5>
                    <form action="{{ route('settings.update-preferences') }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="mb-3">
                            <label class="form-label d-block">Notificações</label>
                            <div class="form-check form-switch mb-2">
                                <input class="form-check-input" type="checkbox" id="notify_bets" name="notify_bets" {{ $user->preferences->notify_bets ? 'checked' : '' }}>
                                <label class="form-check-label" for="notify_bets">Apostas</label>
                            </div>
                            <div class="form-check form-switch mb-2">
                                <input class="form-check-input" type="checkbox" id="notify_groups" name="notify_groups" {{ $user->preferences->notify_groups ? 'checked' : '' }}>
                                <label class="form-check-label" for="notify_groups">Grupos</label>
                            </div>
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="notify_draws" name="notify_draws" {{ $user->preferences->notify_draws ? 'checked' : '' }}>
                                <label class="form-check-label" for="notify_draws">Sorteios</label>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label d-block">Privacidade</label>
                            <div class="form-check form-switch mb-2">
                                <input class="form-check-input" type="checkbox" id="show_balance" name="show_balance" {{ $user->preferences->show_balance ? 'checked' : '' }}>
                                <label class="form-check-label" for="show_balance">Mostrar saldo no perfil</label>
                            </div>
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="show_activity" name="show_activity" {{ $user->preferences->show_activity ? 'checked' : '' }}>
                                <label class="form-check-label" for="show_activity">Mostrar atividades no perfil</label>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-2"></i> Salvar Preferências
                        </button>
                    </form>
                </div>
            </div>

            <!-- Conta -->
            <div class="card mt-4">
                <div class="card-body">
                    <h5 class="card-title mb-4">Conta</h5>
                    <div class="d-grid gap-2">
                        <button type="button" class="btn btn-outline-danger" data-bs-toggle="modal" data-bs-target="#deleteAccountModal">
                            <i class="fas fa-trash-alt me-2"></i> Excluir Conta
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Excluir Conta -->
<div class="modal fade" id="deleteAccountModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('settings.delete-account') }}" method="POST">
                @csrf
                @method('DELETE')
                <div class="modal-header">
                    <h5 class="modal-title">Excluir Conta</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p class="text-danger mb-0">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        Atenção: Esta ação não pode ser desfeita. Todos os seus dados serão permanentemente excluídos.
                    </p>
                    <div class="mt-3">
                        <label for="delete_confirmation" class="form-label">Digite "EXCLUIR" para confirmar</label>
                        <input type="text" class="form-control" id="delete_confirmation" name="confirmation" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-danger">Excluir Conta</button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.getElementById('deleteAccountModal').addEventListener('show.bs.modal', function (event) {
    const button = event.relatedTarget;
    const modal = this;
    const form = modal.querySelector('form');
    const confirmationInput = modal.querySelector('#delete_confirmation');
    const submitButton = modal.querySelector('button[type="submit"]');

    form.addEventListener('submit', function (event) {
        if (confirmationInput.value !== 'EXCLUIR') {
            event.preventDefault();
            confirmationInput.classList.add('is-invalid');
        }
    });

    confirmationInput.addEventListener('input', function () {
        if (this.value === 'EXCLUIR') {
            this.classList.remove('is-invalid');
            submitButton.disabled = false;
        } else {
            this.classList.add('is-invalid');
            submitButton.disabled = true;
        }
    });
});
</script>
@endpush
@endsection
