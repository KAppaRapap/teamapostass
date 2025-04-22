<div class="card mb-4">
    <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Minha Carteira Virtual</h5>
        <a href="#" class="btn btn-sm btn-outline-primary disabled" tabindex="-1" aria-disabled="true">Ver Extrato</a>
    </div>
    <div class="card-body">
        <div class="d-flex align-items-center mb-3">
            <div class="me-3">
                <i class="fas fa-wallet fa-2x text-success"></i>
            </div>
            <div>
                <h6 class="text-muted mb-1">Saldo Atual</h6>
                <h3 class="mb-0 text-success">€{{ number_format(Auth::user()->virtual_balance ?? 0, 2) }}</h3>
            </div>
        </div>
        <div class="row g-2">
            <div class="col">
                <a href="{{ route('wallet.deposit') }}" class="btn btn-success w-100"><i class="fas fa-arrow-down me-1"></i> Depositar</a>
            </div>
            <div class="col">
                <a href="#" class="btn btn-outline-danger w-100 disabled" tabindex="-1" aria-disabled="true"><i class="fas fa-arrow-up me-1"></i> Levantar</a>
            </div>
        </div>
    </div>
</div>
