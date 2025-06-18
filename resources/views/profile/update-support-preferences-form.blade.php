@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card shadow-sm border-0">
                <div class="card-body p-4 p-md-5">
                    <div class="text-center mb-4">
                        <i class="fas fa-headset fa-3x text-primary mb-3"></i>
                        <h1 class="h3">Notificações de Suporte</h1>
                        <p class="text-muted">
                            Configure suas notificações relacionadas ao suporte.
                        </p>
                    </div>

                    <form method="POST" action="{{ route('user.support-preferences.update') }}">
                        @csrf
                        @method('PUT')

                        <!-- Ticket Notifications -->
                        <div class="mb-4">
                            <h5 class="mb-3">
                                <i class="fas fa-ticket-alt me-2"></i> Notificações de Ticket
                            </h5>
                            <div class="form-check mb-2">
                                <input type="checkbox" class="form-check-input" id="ticket_created" 
                                       name="ticket_created" {{ auth()->user()->ticket_created ? 'checked' : '' }}>
                                <label class="form-check-label" for="ticket_created">
                                    Ticket criado
                                </label>
                                <small class="text-muted d-block mt-1">
                                    Seja notificado quando um ticket for criado.
                                </small>
                            </div>
                            <div class="form-check mb-2">
                                <input type="checkbox" class="form-check-input" id="ticket_updated" 
                                       name="ticket_updated" {{ auth()->user()->ticket_updated ? 'checked' : '' }}>
                                <label class="form-check-label" for="ticket_updated">
                                    Ticket atualizado
                                </label>
                                <small class="text-muted d-block mt-1">
                                    Receba notificações sobre atualizações de tickets.
                                </small>
                            </div>
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" id="ticket_closed" 
                                       name="ticket_closed" {{ auth()->user()->ticket_closed ? 'checked' : '' }}>
                                <label class="form-check-label" for="ticket_closed">
                                    Ticket fechado
                                </label>
                                <small class="text-muted d-block mt-1">
                                    Seja notificado quando um ticket for fechado.
                                </small>
                            </div>
                        </div>

                        <!-- Support Messages -->
                        <div class="mb-4">
                            <h5 class="mb-3">
                                <i class="fas fa-comments me-2"></i> Mensagens de Suporte
                            </h5>
                            <div class="form-check mb-2">
                                <input type="checkbox" class="form-check-input" id="message_received" 
                                       name="message_received" {{ auth()->user()->message_received ? 'checked' : '' }}>
                                <label class="form-check-label" for="message_received">
                                    Mensagem recebida
                                </label>
                                <small class="text-muted d-block mt-1">
                                    Receba notificações sobre novas mensagens.
                                </small>
                            </div>
                            <div class="form-check mb-2">
                                <input type="checkbox" class="form-check-input" id="message_replied" 
                                       name="message_replied" {{ auth()->user()->message_replied ? 'checked' : '' }}>
                                <label class="form-check-label" for="message_replied">
                                    Mensagem respondida
                                </label>
                                <small class="text-muted d-block mt-1">
                                    Seja notificado sobre respostas às mensagens.
                                </small>
                            </div>
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" id="message_mention" 
                                       name="message_mention" {{ auth()->user()->message_mention ? 'checked' : '' }}>
                                <label class="form-check-label" for="message_mention">
                                    Menções em mensagens
                                </label>
                                <small class="text-muted d-block mt-1">
                                    Receba notificações sobre menções em mensagens.
                                </small>
                            </div>
                        </div>

                        <!-- Support Status -->
                        <div class="mb-4">
                            <h5 class="mb-3">
                                <i class="fas fa-info-circle me-2"></i> Status de Suporte
                            </h5>
                            <div class="form-check mb-2">
                                <input type="checkbox" class="form-check-input" id="status_priority" 
                                       name="status_priority" {{ auth()->user()->status_priority ? 'checked' : '' }}>
                                <label class="form-check-label" for="status_priority">
                                    Mudança de prioridade
                                </label>
                                <small class="text-muted d-block mt-1">
                                    Seja notificado sobre mudanças de prioridade.
                                </small>
                            </div>
                            <div class="form-check mb-2">
                                <input type="checkbox" class="form-check-input" id="status_assigned" 
                                       name="status_assigned" {{ auth()->user()->status_assigned ? 'checked' : '' }}>
                                <label class="form-check-label" for="status_assigned">
                                    Ticket atribuído
                                </label>
                                <small class="text-muted d-block mt-1">
                                    Receba notificações sobre tickets atribuídos.
                                </small>
                            </div>
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" id="status_resolved" 
                                       name="status_resolved" {{ auth()->user()->status_resolved ? 'checked' : '' }}>
                                <label class="form-check-label" for="status_resolved">
                                    Ticket resolvido
                                </label>
                                <small class="text-muted d-block mt-1">
                                    Seja notificado sobre tickets resolvidos.
                                </small>
                            </div>
                        </div>

                        <!-- Support Feedback -->
                        <div class="mb-4">
                            <h5 class="mb-3">
                                <i class="fas fa-star me-2"></i> Feedback de Suporte
                            </h5>
                            <div class="form-check mb-2">
                                <input type="checkbox" class="form-check-input" id="feedback_request" 
                                       name="feedback_request" {{ auth()->user()->feedback_request ? 'checked' : '' }}>
                                <label class="form-check-label" for="feedback_request">
                                    Solicitação de feedback
                                </label>
                                <small class="text-muted d-block mt-1">
                                    Receba notificações sobre solicitações de feedback.
                                </small>
                            </div>
                            <div class="form-check mb-2">
                                <input type="checkbox" class="form-check-input" id="feedback_received" 
                                       name="feedback_received" {{ auth()->user()->feedback_received ? 'checked' : '' }}>
                                <label class="form-check-label" for="feedback_received">
                                    Feedback recebido
                                </label>
                                <small class="text-muted d-block mt-1">
                                    Seja notificado sobre feedback recebido.
                                </small>
                            </div>
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" id="feedback_rating" 
                                       name="feedback_rating" {{ auth()->user()->feedback_rating ? 'checked' : '' }}>
                                <label class="form-check-label" for="feedback_rating">
                                    Avaliação de suporte
                                </label>
                                <small class="text-muted d-block mt-1">
                                    Receba notificações sobre avaliações de suporte.
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

            <!-- Support Tips -->
            <div class="card shadow-sm border-0 mt-4">
                <div class="card-body p-4">
                    <h5 class="card-title mb-3">
                        <i class="fas fa-info-circle me-2"></i> Dicas
                    </h5>
                    <ul class="list-unstyled text-muted mb-0">
                        <li class="mb-2">
                            <i class="fas fa-check-circle me-2"></i> Mantenha-se informado sobre seus tickets
                        </li>
                        <li class="mb-2">
                            <i class="fas fa-check-circle me-2"></i> Configure notificações para mensagens importantes
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