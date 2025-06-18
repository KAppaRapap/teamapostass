<!-- Carteira Virtual -->
<div class="card mb-4">
    <div class="card-header py-3 d-flex justify-content-between align-items-center">
        <h5 class="mb-0 text-dark">Carteira Virtual</h5>
    </div>
    <div class="card-body">
        <div class="row align-items-center">
            <div class="col-md-6">
                <div class="d-flex align-items-center">
                    <div class="icon-box me-3">
                        <i class="fas fa-wallet fa-2x text-primary-blue"></i>
                    </div>
                    <div>
                        <h6 class="text-secondary-text-color mb-1">Saldo Disponível</h6>
                        <h3 class="mb-0 text-success-green virtual-wallet-balance">€{{ number_format(Auth::user()->virtual_balance, 2) }}</h3>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="d-flex justify-content-md-end mt-3 mt-md-0">
                    <div class="text-end">
                        <h6 class="text-secondary-text-color mb-1">Total Movimentado</h6>
                        <h4 class="mb-0 text-dark">€{{ number_format(Auth::user()->total_transactions, 2) }}</h4>
                        <a href="{{ route('wallet.index') }}" class="btn btn-primary btn-sm mt-3">
                            <i class="fas fa-receipt me-1"></i> Ver Extrato
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
