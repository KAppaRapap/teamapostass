@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row mb-4">
        <div class="col-12">
            <h1 class="h3 mb-0">Dashboard</h1>
            <p class="text-muted">Bem-vindo de volta, {{ auth()->user()->name }}!</p>
        </div>
    </div>

    <!-- Cards de Estatísticas -->
    <div class="row g-4 mb-4">
        <div class="col-md-6 col-lg-3">
            <div class="card h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="bg-primary bg-opacity-10 p-3 rounded">
                                <i class="fas fa-users text-primary fa-2x"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="card-title mb-1">Meus Grupos</h6>
                            <h3 class="mb-0">{{ auth()->user()->groups->count() }}</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-lg-3">
            <div class="card h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="bg-success bg-opacity-10 p-3 rounded">
                                <i class="fas fa-ticket-alt text-success fa-2x"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="card-title mb-1">Apostas Ativas</h6>
                            <h3 class="mb-0">{{ auth()->user()->bettingSlips()->where('status', 'pending')->count() }}</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-lg-3">
            <div class="card h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="bg-warning bg-opacity-10 p-3 rounded">
                                <i class="fas fa-trophy text-warning fa-2x"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="card-title mb-1">Ganhos Totais</h6>
                            <h3 class="mb-0">€{{ number_format(auth()->user()->bettingSlips()->where('status', 'won')->sum('prize_amount'), 2) }}</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-lg-3">
            <div class="card h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="bg-info bg-opacity-10 p-3 rounded">
                                <i class="fas fa-wallet text-info fa-2x"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="card-title mb-1">Saldo Atual</h6>
                            <h3 class="mb-0">€{{ number_format(auth()->user()->virtual_balance, 2) }}</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <!-- Próximos Sorteios -->
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header bg-transparent border-0">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0">Próximos Sorteios</h5>
                        <a href="{{ route('games.upcoming-draws') }}" class="btn btn-sm btn-primary">Ver Todos</a>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead>
                                <tr>
                                    <th>Jogo</th>
                                    <th>Data</th>
                                    <th>Prêmio</th>
                                    <th>Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($upcomingDraws as $draw)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <img src="{{ asset('storage/' . $draw->game->logo) }}" alt="{{ $draw->game->name }}" class="rounded me-2" width="32">
                                            <span>{{ $draw->game->name }}</span>
                                        </div>
                                    </td>
                                    <td>{{ $draw->draw_date->format('d/m/Y H:i') }}</td>
                                    <td>€{{ number_format($draw->jackpot, 2) }}</td>
                                    <td>
                                        @php
                                            $userGroup = auth()->user()->groups->where('game_id', $draw->game_id)->first();
                                        @endphp
                                        @if($userGroup)
                                            <a href="{{ route('betting-slips.create', ['group' => $userGroup->id]) }}" class="btn btn-sm btn-outline-primary">
                                                <i class="fas fa-plus me-1"></i> Apostar
                                            </a>
                                        @else
                                            <span class="text-muted small">Entre em um grupo para apostar</span>
                                        @endif
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="text-center py-4">
                                        <div class="text-muted">
                                            <i class="fas fa-calendar-times fa-2x mb-2"></i>
                                            <p class="mb-0">Nenhum sorteio próximo</p>
                                        </div>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Atividades Recentes -->
        <div class="col-lg-4">
            <div class="card">
                <div class="card-header bg-transparent border-0">
                    <h5 class="card-title mb-0">Atividades Recentes</h5>
                </div>
                <div class="card-body p-0">
                    <div class="list-group list-group-flush">
                        @forelse($recentActivities as $activity)
                        <div class="list-group-item">
                            <div class="d-flex align-items-center">
                                <div class="flex-shrink-0">
                                    @switch($activity->type)
                                        @case('bet_placed')
                                            <div class="bg-primary bg-opacity-10 p-2 rounded">
                                                <i class="fas fa-ticket-alt text-primary"></i>
                                            </div>
                                            @break
                                        @case('bet_won')
                                            <div class="bg-success bg-opacity-10 p-2 rounded">
                                                <i class="fas fa-trophy text-success"></i>
                                            </div>
                                            @break
                                        @case('group_joined')
                                            <div class="bg-info bg-opacity-10 p-2 rounded">
                                                <i class="fas fa-users text-info"></i>
                                            </div>
                                            @break
                                        @default
                                            <div class="bg-secondary bg-opacity-10 p-2 rounded">
                                                <i class="fas fa-bell text-secondary"></i>
                                            </div>
                                    @endswitch
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <p class="mb-0">{{ $activity->description }}</p>
                                    <small class="text-muted">{{ $activity->created_at->diffForHumans() }}</small>
                                </div>
                            </div>
                        </div>
                        @empty
                        <div class="list-group-item text-center py-4">
                            <div class="text-muted">
                                <i class="fas fa-history fa-2x mb-2"></i>
                                <p class="mb-0">Nenhuma atividade recente</p>
                            </div>
                        </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Atualizar estatísticas a cada 5 minutos
    setInterval(function() {
        location.reload();
    }, 300000);
</script>
@endpush
