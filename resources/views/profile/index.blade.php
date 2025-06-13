@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row mb-4">
        <div class="col-12">
            <h1 class="h3 mb-0">Perfil</h1>
            <p class="text-muted">Visualize e gerencie seu perfil</p>
        </div>
    </div>

    <div class="row g-4">
        <!-- Informações do Perfil -->
        <div class="col-lg-4">
            <div class="card">
                <div class="card-body text-center">
                    <div class="mb-4">
                        <img src="{{ $user->profile_photo ? asset('storage/' . $user->profile_photo) : asset('img/default-avatar.png') }}" 
                             class="rounded-circle mb-3" 
                             width="120" 
                             height="120" 
                             alt="Foto de Perfil">
                        <h4 class="mb-1">{{ $user->name }}</h4>
                        <p class="text-muted mb-3">{{ $user->email }}</p>
                        <a href="{{ route('settings.index') }}" class="btn btn-outline-primary">
                            <i class="fas fa-cog me-2"></i> Editar Perfil
                        </a>
                    </div>

                    <div class="border-top pt-4">
                        <div class="row g-3">
                            <div class="col-6">
                                <div class="d-flex flex-column">
                                    <span class="text-muted mb-1">Grupos</span>
                                    <span class="h4 mb-0">{{ $user->groups->count() }}</span>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="d-flex flex-column">
                                    <span class="text-muted mb-1">Apostas</span>
                                    <span class="h4 mb-0">{{ $user->bettingSlips->count() }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            @if($user->preferences->show_balance)
            <div class="card mt-4">
                <div class="card-body">
                    <h5 class="card-title mb-3">Saldo</h5>
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="text-muted">Disponível</span>
                        <span class="h4 mb-0">€{{ number_format($user->virtual_balance, 2) }}</span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="text-muted">Total Ganho</span>
                        <span class="h4 mb-0 text-success">€{{ number_format($user->activities()->where('type', 'bet_won')->sum('data->prize_amount'), 2) }}</span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="text-muted">Total Apostado</span>
                        <span class="h4 mb-0 text-danger">€{{ number_format($user->activities()->where('type', 'bet_placed')->sum('data->amount'), 2) }}</span>
                    </div>
                </div>
            </div>
            @endif
        </div>

        <!-- Atividades Recentes -->
        @if($user->preferences->show_activity)
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header bg-transparent border-0">
                    <h5 class="card-title mb-0">Atividades Recentes</h5>
                </div>
                <div class="card-body p-0">
                    <div class="list-group list-group-flush">
                        @forelse($user->recentActivities() as $activity)
                        <div class="list-group-item">
                            <div class="d-flex align-items-center">
                                <div class="flex-shrink-0">
                                    @switch($activity->type)
                                        @case('bet_placed')
                                            <div class="avatar bg-primary bg-opacity-10 text-primary">
                                                <i class="fas fa-ticket-alt"></i>
                                            </div>
                                            @break
                                        @case('bet_won')
                                            <div class="avatar bg-success bg-opacity-10 text-success">
                                                <i class="fas fa-trophy"></i>
                                            </div>
                                            @break
                                        @case('bet_lost')
                                            <div class="avatar bg-danger bg-opacity-10 text-danger">
                                                <i class="fas fa-times"></i>
                                            </div>
                                            @break
                                        @case('group_joined')
                                            <div class="avatar bg-info bg-opacity-10 text-info">
                                                <i class="fas fa-users"></i>
                                            </div>
                                            @break
                                        @case('group_left')
                                            <div class="avatar bg-warning bg-opacity-10 text-warning">
                                                <i class="fas fa-sign-out-alt"></i>
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
                                        <h6 class="mb-1">{{ $activity->description }}</h6>
                                        <small class="text-muted">{{ $activity->created_at->diffForHumans() }}</small>
                                    </div>
                                    @if($activity->group)
                                    <p class="mb-0 text-muted">
                                        <i class="fas fa-users me-1"></i>
                                        {{ $activity->group->name }}
                                    </p>
                                    @endif
                                </div>
                            </div>
                        </div>
                        @empty
                        <div class="text-center py-5">
                            <div class="text-muted">
                                <i class="fas fa-history fa-3x mb-3"></i>
                                <p class="mb-0">Nenhuma atividade encontrada</p>
                            </div>
                        </div>
                        @endforelse
                    </div>
                </div>
            </div>

            <!-- Grupos -->
            <div class="card mt-4">
                <div class="card-header bg-transparent border-0">
                    <h5 class="card-title mb-0">Meus Grupos</h5>
                </div>
                <div class="card-body p-0">
                    <div class="list-group list-group-flush">
                        @forelse($user->groups as $group)
                        <div class="list-group-item">
                            <div class="d-flex align-items-center">
                                <div class="flex-shrink-0">
                                    <div class="avatar bg-primary bg-opacity-10 text-primary">
                                        <i class="fas fa-users"></i>
                                    </div>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <h6 class="mb-1">{{ $group->name }}</h6>
                                        <span class="badge bg-primary">{{ $group->members->count() }} membros</span>
                                    </div>
                                    <p class="mb-0 text-muted">{{ Str::limit($group->description, 100) }}</p>
                                </div>
                                <div class="flex-shrink-0 ms-3">
                                    <a href="{{ route('groups.show', $group) }}" class="btn btn-sm btn-outline-primary">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                        @empty
                        <div class="text-center py-5">
                            <div class="text-muted">
                                <i class="fas fa-users fa-3x mb-3"></i>
                                <p class="mb-0">Você ainda não participa de nenhum grupo</p>
                            </div>
                        </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
        @endif
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