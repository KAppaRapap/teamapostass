@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card shadow-sm border-0">
                <div class="card-body p-4 p-md-5">
                    <div class="text-center mb-4">
                        <i class="fas fa-cog fa-3x text-primary mb-3"></i>
                        <h1 class="h3">Notificações de Sistema</h1>
                        <p class="text-muted">
                            Configure suas notificações relacionadas ao sistema.
                        </p>
                    </div>

                    <form method="POST" action="{{ route('user.system-preferences.update') }}">
                        @csrf
                        @method('PUT')

                        <!-- System Updates -->
                        <div class="mb-4">
                            <h5 class="mb-3">
                                <i class="fas fa-sync me-2"></i> Atualizações do Sistema
                            </h5>
                            <div class="form-check mb-2">
                                <input type="checkbox" class="form-check-input" id="update_available" 
                                       name="update_available" {{ auth()->user()->update_available ? 'checked' : '' }}>
                                <label class="form-check-label" for="update_available">
                                    Atualizações disponíveis
                                </label>
                                <small class="text-muted d-block mt-1">
                                    Seja notificado quando houver atualizações disponíveis.
                                </small>
                            </div>
                            <div class="form-check mb-2">
                                <input type="checkbox" class="form-check-input" id="update_installed" 
                                       name="update_installed" {{ auth()->user()->update_installed ? 'checked' : '' }}>
                                <label class="form-check-label" for="update_installed">
                                    Atualizações instaladas
                                </label>
                                <small class="text-muted d-block mt-1">
                                    Receba notificações sobre atualizações instaladas.
                                </small>
                            </div>
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" id="update_failed" 
                                       name="update_failed" {{ auth()->user()->update_failed ? 'checked' : '' }}>
                                <label class="form-check-label" for="update_failed">
                                    Falhas na atualização
                                </label>
                                <small class="text-muted d-block mt-1">
                                    Seja notificado sobre falhas em atualizações.
                                </small>
                            </div>
                        </div>

                        <!-- System Status -->
                        <div class="mb-4">
                            <h5 class="mb-3">
                                <i class="fas fa-server me-2"></i> Status do Sistema
                            </h5>
                            <div class="form-check mb-2">
                                <input type="checkbox" class="form-check-input" id="status_maintenance" 
                                       name="status_maintenance" {{ auth()->user()->status_maintenance ? 'checked' : '' }}>
                                <label class="form-check-label" for="status_maintenance">
                                    Manutenção
                                </label>
                                <small class="text-muted d-block mt-1">
                                    Receba notificações sobre manutenção do sistema.
                                </small>
                            </div>
                            <div class="form-check mb-2">
                                <input type="checkbox" class="form-check-input" id="status_outage" 
                                       name="status_outage" {{ auth()->user()->status_outage ? 'checked' : '' }}>
                                <label class="form-check-label" for="status_outage">
                                    Indisponibilidade
                                </label>
                                <small class="text-muted d-block mt-1">
                                    Seja notificado sobre indisponibilidade do sistema.
                                </small>
                            </div>
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" id="status_resolved" 
                                       name="status_resolved" {{ auth()->user()->status_resolved ? 'checked' : '' }}>
                                <label class="form-check-label" for="status_resolved">
                                    Problemas resolvidos
                                </label>
                                <small class="text-muted d-block mt-1">
                                    Receba notificações quando problemas forem resolvidos.
                                </small>
                            </div>
                        </div>

                        <!-- System Performance -->
                        <div class="mb-4">
                            <h5 class="mb-3">
                                <i class="fas fa-tachometer-alt me-2"></i> Desempenho do Sistema
                            </h5>
                            <div class="form-check mb-2">
                                <input type="checkbox" class="form-check-input" id="performance_high" 
                                       name="performance_high" {{ auth()->user()->performance_high ? 'checked' : '' }}>
                                <label class="form-check-label" for="performance_high">
                                    Alta carga
                                </label>
                                <small class="text-muted d-block mt-1">
                                    Seja notificado sobre alta carga no sistema.
                                </small>
                            </div>
                            <div class="form-check mb-2">
                                <input type="checkbox" class="form-check-input" id="performance_slow" 
                                       name="performance_slow" {{ auth()->user()->performance_slow ? 'checked' : '' }}>
                                <label class="form-check-label" for="performance_slow">
                                    Desempenho lento
                                </label>
                                <small class="text-muted d-block mt-1">
                                    Receba notificações sobre desempenho lento.
                                </small>
                            </div>
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" id="performance_normal" 
                                       name="performance_normal" {{ auth()->user()->performance_normal ? 'checked' : '' }}>
                                <label class="form-check-label" for="performance_normal">
                                    Desempenho normal
                                </label>
                                <small class="text-muted d-block mt-1">
                                    Seja notificado quando o desempenho voltar ao normal.
                                </small>
                            </div>
                        </div>

                        <!-- System Resources -->
                        <div class="mb-4">
                            <h5 class="mb-3">
                                <i class="fas fa-microchip me-2"></i> Recursos do Sistema
                            </h5>
                            <div class="form-check mb-2">
                                <input type="checkbox" class="form-check-input" id="resource_storage" 
                                       name="resource_storage" {{ auth()->user()->resource_storage ? 'checked' : '' }}>
                                <label class="form-check-label" for="resource_storage">
                                    Armazenamento
                                </label>
                                <small class="text-muted d-block mt-1">
                                    Receba notificações sobre uso de armazenamento.
                                </small>
                            </div>
                            <div class="form-check mb-2">
                                <input type="checkbox" class="form-check-input" id="resource_memory" 
                                       name="resource_memory" {{ auth()->user()->resource_memory ? 'checked' : '' }}>
                                <label class="form-check-label" for="resource_memory">
                                    Memória
                                </label>
                                <small class="text-muted d-block mt-1">
                                    Seja notificado sobre uso de memória.
                                </small>
                            </div>
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" id="resource_cpu" 
                                       name="resource_cpu" {{ auth()->user()->resource_cpu ? 'checked' : '' }}>
                                <label class="form-check-label" for="resource_cpu">
                                    CPU
                                </label>
                                <small class="text-muted d-block mt-1">
                                    Receba notificações sobre uso de CPU.
                                </small>
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

            <!-- System Tips -->
            <div class="card shadow-sm border-0 mt-4">
                <div class="card-body p-4">
                    <h5 class="card-title mb-3">
                        <i class="fas fa-info-circle me-2"></i> Dicas
                    </h5>
                    <ul class="list-unstyled text-muted mb-0">
                        <li class="mb-2">
                            <i class="fas fa-check-circle me-2"></i> Mantenha-se informado sobre atualizações do sistema
                        </li>
                        <li class="mb-2">
                            <i class="fas fa-check-circle me-2"></i> Configure alertas para problemas importantes
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

small.text-muted {
    font-size: 0.85rem;
}
</style>
@endsection 