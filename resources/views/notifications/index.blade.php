@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row mb-4">
        <div class="col-12">
            <h1 class="h3 mb-0">Notificações</h1>
            <p class="text-muted">Acompanhe suas notificações e atualizações</p>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8 mx-auto">
            <div class="card">
                <div class="card-header bg-transparent border-0">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0">Todas as Notificações</h5>
                        @if($notifications->isNotEmpty())
                        <form action="{{ route('notifications.mark-all-as-read') }}" method="POST" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-sm btn-outline-primary">
                                <i class="fas fa-check-double me-1"></i> Marcar todas como lidas
                            </button>
                        </form>
                        @endif
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="list-group list-group-flush">
                        @forelse($notifications as $notification)
                        <div class="list-group-item {{ $notification->read_at ? '' : 'bg-light' }}">
                            <div class="d-flex align-items-center">
                                <div class="flex-shrink-0">
                                    @switch($notification->type)
                                        @case('App\Notifications\BetWon')
                                            <div class="avatar bg-success bg-opacity-10 text-success">
                                                <i class="fas fa-trophy"></i>
                                            </div>
                                            @break
                                        @case('App\Notifications\BetLost')
                                            <div class="avatar bg-danger bg-opacity-10 text-danger">
                                                <i class="fas fa-times"></i>
                                            </div>
                                            @break
                                        @case('App\Notifications\GroupInvitation')
                                            <div class="avatar bg-primary bg-opacity-10 text-primary">
                                                <i class="fas fa-users"></i>
                                            </div>
                                            @break
                                        @case('App\Notifications\DrawCompleted')
                                            <div class="avatar bg-info bg-opacity-10 text-info">
                                                <i class="fas fa-flag-checkered"></i>
                                            </div>
                                            @break
                                        @default
                                            <div class="avatar bg-secondary bg-opacity-10 text-secondary">
                                                <i class="fas fa-bell"></i>
                                            </div>
                                    @endswitch
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <h6 class="mb-1">{{ $notification->data['title'] ?? 'Notificação' }}</h6>
                                        <small class="text-muted">{{ $notification->created_at->diffForHumans() }}</small>
                                    </div>
                                    <p class="mb-1">{{ $notification->data['message'] ?? '' }}</p>
                                    @if(isset($notification->data['action_url']))
                                    <a href="{{ $notification->data['action_url'] }}" class="btn btn-sm btn-outline-primary mt-2">
                                        {{ $notification->data['action_text'] ?? 'Ver Detalhes' }}
                                    </a>
                                    @endif
                                </div>
                                @unless($notification->read_at)
                                <div class="flex-shrink-0 ms-3">
                                    <form action="{{ route('notifications.mark-as-read', $notification->id) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-link text-muted">
                                            <i class="fas fa-check"></i>
                                        </button>
                                    </form>
                                </div>
                                @endunless
                            </div>
                        </div>
                        @empty
                        <div class="text-center py-5">
                            <div class="text-muted">
                                <i class="fas fa-bell-slash fa-3x mb-3"></i>
                                <p class="mb-0">Nenhuma notificação encontrada</p>
                            </div>
                        </div>
                        @endforelse
                    </div>
                </div>
                @if($notifications->hasPages())
                <div class="card-footer bg-transparent border-0">
                    {{ $notifications->links() }}
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

<style>
.avatar {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.2rem;
}
</style>
@endsection
