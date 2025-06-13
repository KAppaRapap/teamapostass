@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card shadow-sm border-0">
                <div class="card-body p-4 p-md-5">
                    <div class="text-center mb-4">
                        <i class="fas fa-users fa-3x text-primary mb-3"></i>
                        <h1 class="h3">Notificações de Grupos</h1>
                        <p class="text-muted">
                            Configure suas notificações relacionadas a grupos.
                        </p>
                    </div>

                    <form method="POST" action="{{ route('user.group-preferences.update') }}">
                        @csrf
                        @method('PUT')

                        <!-- Group Invitations -->
                        <div class="mb-4">
                            <h5 class="mb-3">
                                <i class="fas fa-user-plus me-2"></i> Convites de Grupo
                            </h5>
                            <div class="form-check mb-2">
                                <input type="checkbox" class="form-check-input" id="invite_received" 
                                       name="invite_received" {{ auth()->user()->invite_received ? 'checked' : '' }}>
                                <label class="form-check-label" for="invite_received">
                                    Convite recebido
                                </label>
                                <small class="text-muted d-block mt-1">
                                    Seja notificado quando receber um convite.
                                </small>
                            </div>
                            <div class="form-check mb-2">
                                <input type="checkbox" class="form-check-input" id="invite_accepted" 
                                       name="invite_accepted" {{ auth()->user()->invite_accepted ? 'checked' : '' }}>
                                <label class="form-check-label" for="invite_accepted">
                                    Convite aceito
                                </label>
                                <small class="text-muted d-block mt-1">
                                    Receba notificações sobre convites aceitos.
                                </small>
                            </div>
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" id="invite_rejected" 
                                       name="invite_rejected" {{ auth()->user()->invite_rejected ? 'checked' : '' }}>
                                <label class="form-check-label" for="invite_rejected">
                                    Convite rejeitado
                                </label>
                                <small class="text-muted d-block mt-1">
                                    Seja notificado sobre convites rejeitados.
                                </small>
                            </div>
                        </div>

                        <!-- Group Activities -->
                        <div class="mb-4">
                            <h5 class="mb-3">
                                <i class="fas fa-calendar-check me-2"></i> Atividades do Grupo
                            </h5>
                            <div class="form-check mb-2">
                                <input type="checkbox" class="form-check-input" id="activity_new" 
                                       name="activity_new" {{ auth()->user()->activity_new ? 'checked' : '' }}>
                                <label class="form-check-label" for="activity_new">
                                    Novas atividades
                                </label>
                                <small class="text-muted d-block mt-1">
                                    Receba notificações sobre novas atividades.
                                </small>
                            </div>
                            <div class="form-check mb-2">
                                <input type="checkbox" class="form-check-input" id="activity_event" 
                                       name="activity_event" {{ auth()->user()->activity_event ? 'checked' : '' }}>
                                <label class="form-check-label" for="activity_event">
                                    Eventos do grupo
                                </label>
                                <small class="text-muted d-block mt-1">
                                    Seja notificado sobre eventos do grupo.
                                </small>
                            </div>
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" id="activity_achievement" 
                                       name="activity_achievement" {{ auth()->user()->activity_achievement ? 'checked' : '' }}>
                                <label class="form-check-label" for="activity_achievement">
                                    Conquistas do grupo
                                </label>
                                <small class="text-muted d-block mt-1">
                                    Receba notificações sobre conquistas do grupo.
                                </small>
                            </div>
                        </div>

                        <!-- Group Messages -->
                        <div class="mb-4">
                            <h5 class="mb-3">
                                <i class="fas fa-comment-dots me-2"></i> Mensagens do Grupo
                            </h5>
                            <div class="form-check mb-2">
                                <input type="checkbox" class="form-check-input" id="message_new" 
                                       name="message_new" {{ auth()->user()->message_new ? 'checked' : '' }}>
                                <label class="form-check-label" for="message_new">
                                    Novas mensagens
                                </label>
                                <small class="text-muted d-block mt-1">
                                    Seja notificado sobre novas mensagens.
                                </small>
                            </div>
                            <div class="form-check mb-2">
                                <input type="checkbox" class="form-check-input" id="message_mention" 
                                       name="message_mention" {{ auth()->user()->message_mention ? 'checked' : '' }}>
                                <label class="form-check-label" for="message_mention">
                                    Menções em mensagens
                                </label>
                                <small class="text-muted d-block mt-1">
                                    Receba notificações sobre menções em mensagens.
                                </small>
                            </div>
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" id="message_reply" 
                                       name="message_reply" {{ auth()->user()->message_reply ? 'checked' : '' }}>
                                <label class="form-check-label" for="message_reply">
                                    Respostas em mensagens
                                </label>
                                <small class="text-muted d-block mt-1">
                                    Seja notificado sobre respostas em mensagens.
                                </small>
                            </div>
                        </div>

                        <!-- Group Updates -->
                        <div class="mb-4">
                            <h5 class="mb-3">
                                <i class="fas fa-sync me-2"></i> Atualizações do Grupo
                            </h5>
                            <div class="form-check mb-2">
                                <input type="checkbox" class="form-check-input" id="update_general" 
                                       name="update_general" {{ auth()->user()->update_general ? 'checked' : '' }}>
                                <label class="form-check-label" for="update_general">
                                    Atualizações gerais
                                </label>
                                <small class="text-muted d-block mt-1">
                                    Receba notificações sobre atualizações gerais.
                                </small>
                            </div>
                            <div class="form-check mb-2">
                                <input type="checkbox" class="form-check-input" id="update_role" 
                                       name="update_role" {{ auth()->user()->update_role ? 'checked' : '' }}>
                                <label class="form-check-label" for="update_role">
                                    Mudanças de função
                                </label>
                                <small class="text-muted d-block mt-1">
                                    Seja notificado sobre mudanças de função.
                                </small>
                            </div>
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" id="update_settings" 
                                       name="update_settings" {{ auth()->user()->update_settings ? 'checked' : '' }}>
                                <label class="form-check-label" for="update_settings">
                                    Alterações de configurações
                                </label>
                                <small class="text-muted d-block mt-1">
                                    Receba notificações sobre alterações de configurações.
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

            <!-- Group Tips -->
            <div class="card shadow-sm border-0 mt-4">
                <div class="card-body p-4">
                    <h5 class="card-title mb-3">
                        <i class="fas fa-info-circle me-2"></i> Dicas
                    </h5>
                    <ul class="list-unstyled text-muted mb-0">
                        <li class="mb-2">
                            <i class="fas fa-check-circle me-2"></i> Mantenha-se informado sobre as atividades do grupo
                        </li>
                        <li class="mb-2">
                            <i class="fas fa-check-circle me-2"></i> Configure notificações para grupos importantes
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