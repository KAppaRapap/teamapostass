@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0">Notificações</h2>
        @if($notifications->count() > 0)
        <div class="d-flex">
            <form action="{{ route('notifications.mark-all-as-read') }}" method="POST" class="me-2">
                @csrf
                <button type="submit" class="btn btn-outline-primary">
                    <i class="fas fa-check-double me-1"></i> Marcar Todas como Lidas
                </button>
            </form>
            <form action="{{ route('notifications.destroy-all') }}" method="POST">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-outline-danger" onclick="return confirm('Tem certeza que deseja excluir TODAS as notificações? Esta ação não pode ser desfeita.')">
                    <i class="fas fa-trash-alt me-1"></i> Excluir Todas
                </button>
            </form>
        </div>
        @endif
    </div>

    @if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
    @endif

    @if(session('error'))
    <div class="alert alert-danger">
        {{ session('error') }}
    </div>
    @endif

    <div class="card">
        <div class="card-body">
            @if($notifications->count() > 0)
            <div class="list-group list-group-flush">
                @foreach($notifications as $notification)
                <div class="list-group-item list-group-item-action d-flex justify-content-between align-items-center {{ $notification->is_read ? '' : 'bg-light' }}">
                    <div class="d-flex align-items-center">
                        <div class="me-3">
                            @switch($notification->type)
                                @case('group_join')
                                    <div class="notification-icon bg-primary">
                                        <i class="fas fa-users"></i>
                                    </div>
                                    @break
                                @case('group_leave')
                                    <div class="notification-icon bg-warning">
                                        <i class="fas fa-sign-out-alt"></i>
                                    </div>
                                    @break
                                @case('new_draw')
                                    <div class="notification-icon bg-success">
                                        <i class="fas fa-calendar-alt"></i>
                                    </div>
                                    @break
                                @case('draw_result')
                                    <div class="notification-icon bg-info">
                                        <i class="fas fa-trophy"></i>
                                    </div>
                                    @break
                                @default
                                    <div class="notification-icon bg-secondary">
                                        <i class="fas fa-bell"></i>
                                    </div>
                            @endswitch
                        </div>
                        <div>
                            <h6 class="mb-1">{{ $notification->title }}</h6>
                            <p class="mb-1 text-muted">{{ $notification->message }}</p>
                            <small class="text-muted">{{ $notification->created_at->diffForHumans() }}</small>
                        </div>
                    </div>
                    <div class="d-flex">
                        @if(!$notification->is_read)
                        <form action="{{ route('notifications.mark-as-read', $notification) }}" method="POST" class="me-2">
                            @csrf
                            <button type="submit" class="btn btn-sm btn-outline-primary">
                                <i class="fas fa-check"></i>
                            </button>
                        </form>
                        @endif
                        <form action="{{ route('notifications.destroy', $notification) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('Tem certeza que deseja excluir esta notificação?')">
                                <i class="fas fa-trash"></i>
                            </button>
                        </form>
                    </div>
                </div>
                @endforeach
            </div>
            <div class="mt-3">
                {{ $notifications->links('groups.pagination') }}
            </div>
            @else
            <div class="text-center py-5">
                <i class="fas fa-bell-slash fa-4x text-muted mb-3"></i>
                <h4>Nenhuma notificação</h4>
                <p class="text-muted">Você não tem notificações no momento.</p>
            </div>
            @endif
        </div>
    </div>
</div>

<style>
    .notification-icon {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
    }
</style>
@endsection
