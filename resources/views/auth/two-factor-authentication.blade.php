@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card shadow-sm border-0">
                <div class="card-body p-4 p-md-5">
                    <div class="text-center mb-4">
                        <i class="fas fa-shield-alt fa-3x text-primary mb-3"></i>
                        <h1 class="h3">Autenticação em Duas Etapas</h1>
                        <p class="text-muted">
                            Adicione uma camada extra de segurança à sua conta.
                        </p>
                    </div>

                    @if (! auth()->user()->two_factor_secret)
                        {{-- Enable 2FA --}}
                        <form method="POST" action="{{ url('user/two-factor-authentication') }}">
                            @csrf
                            <div class="d-grid">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-lock me-2"></i> Ativar Autenticação em Duas Etapas
                                </button>
                            </div>
                        </form>
                    @else
                        {{-- Show 2FA QR Code --}}
                        <div class="mb-4">
                            <p class="text-muted">
                                A autenticação em duas etapas está ativada. Escaneie o QR code abaixo usando seu 
                                aplicativo autenticador.
                            </p>
                            <div class="text-center">
                                {!! auth()->user()->twoFactorQrCodeSvg() !!}
                            </div>
                        </div>

                        {{-- Show Recovery Codes --}}
                        <div class="mb-4">
                            <p class="text-muted">
                                Armazene estes códigos de recuperação em um local seguro. Eles podem ser usados 
                                para recuperar o acesso à sua conta caso você perca seu dispositivo.
                            </p>
                            <div class="bg-light p-3 rounded">
                                @foreach (json_decode(decrypt(auth()->user()->two_factor_recovery_codes), true) as $code)
                                    <div class="mb-2">
                                        <code>{{ $code }}</code>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        {{-- Regenerate Recovery Codes --}}
                        <form method="POST" action="{{ url('user/two-factor-recovery-codes') }}" class="mb-4">
                            @csrf
                            <div class="d-grid">
                                <button type="submit" class="btn btn-outline-primary">
                                    <i class="fas fa-redo me-2"></i> Gerar Novos Códigos de Recuperação
                                </button>
                            </div>
                        </form>

                        {{-- Disable 2FA --}}
                        <form method="POST" action="{{ url('user/two-factor-authentication') }}">
                            @csrf
                            @method('DELETE')
                            <div class="d-grid">
                                <button type="submit" class="btn btn-danger">
                                    <i class="fas fa-unlock me-2"></i> Desativar Autenticação em Duas Etapas
                                </button>
                            </div>
                        </form>
                    @endif
                </div>
            </div>

            <!-- Security Tips -->
            <div class="card shadow-sm border-0 mt-4">
                <div class="card-body p-4">
                    <h5 class="card-title mb-3">
                        <i class="fas fa-info-circle me-2"></i> Dicas de Segurança
                    </h5>
                    <ul class="list-unstyled text-muted mb-0">
                        <li class="mb-2">
                            <i class="fas fa-check-circle me-2"></i> Use um aplicativo autenticador confiável
                        </li>
                        <li class="mb-2">
                            <i class="fas fa-check-circle me-2"></i> Mantenha seus códigos de recuperação em segurança
                        </li>
                        <li>
                            <i class="fas fa-check-circle me-2"></i> Não compartilhe seus códigos com ninguém
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

.btn-outline-primary {
    color: var(--primary-color);
    border-color: var(--primary-color);
}

.btn-outline-primary:hover {
    background-color: var(--primary-color);
    border-color: var(--primary-color);
}

.btn-danger {
    background-color: #dc3545;
    border-color: #dc3545;
}

.btn-danger:hover {
    background-color: #bb2d3b;
    border-color: #bb2d3b;
}

.list-unstyled li {
    font-size: 0.9rem;
}

code {
    background-color: #f8f9fa;
    padding: 0.2rem 0.4rem;
    border-radius: 4px;
    font-size: 0.9rem;
}
</style>
@endsection 