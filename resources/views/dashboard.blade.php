@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0">Dashboard</h2>
        <div class="d-flex gap-2">
            @if(Auth::user()->is_admin)
                <a href="{{ route('draws.create') }}" class="btn btn-primary"><i class="fas fa-gamepad me-1"></i> Novo Jogo</a>
                <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary"><i class="fas fa-users-cog me-1"></i> Gerenciar Usuários</a>
            @endif
            <a href="{{ route('groups.create') }}" class="btn btn-primary"><i class="fas fa-plus me-1"></i> Novo Grupo</a>
        </div>
    </div>

    <!-- Carteira Virtual -->
    @include('dashboard._virtual_wallet')

    <!-- Stats Cards -->
    <div class="row g-4 mb-4">
        <div class="col-md-3">
            <div class="card h-100">
                <div class="card-body d-flex align-items-center">
                    <div class="me-3 text-primary">
                        <i class="fas fa-users fa-2x"></i>
                    </div>
                    <div>
                        <h6 class="text-muted mb-1">Meus Grupos</h6>
                        <h3 class="mb-0">{{ Auth::user()->groups()->count() }}</h3>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card h-100">
                <div class="card-body d-flex align-items-center">
                    <div class="me-3 text-primary">
                        <i class="fas fa-ticket-alt fa-2x"></i>
                    </div>
                    <div>
                        <h6 class="text-muted mb-1">Apostas Ativas</h6>
                        <h3 class="mb-0">{{ Auth::user()->bettingSlips()->whereHas('draw', function($q){ $q->where('is_completed', false); })->count() }}</h3>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card h-100">
                <div class="card-body d-flex align-items-center">
                    <div class="me-3 text-primary">
                        <i class="fas fa-euro-sign fa-2x"></i>
                    </div>
                    <div>
                        <h6 class="text-muted mb-1">Total Ganho</h6>
                        <h3 class="mb-0">€{{ number_format(Auth::user()->bettingSlips()->sum('winnings'), 2) }}</h3>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card h-100">
                <div class="card-body d-flex align-items-center">
                    <div class="me-3 text-primary">
                        <i class="fas fa-calendar-alt fa-2x"></i>
                    </div>
                    <div>
                        <h6 class="text-muted mb-1">Próximos Jogos</h6>
                        <h3 class="mb-0">{{ App\Models\Draw::where('draw_date', '>', now())->where('is_completed', false)->count() + App\Models\Game::where('name', 'Totobola')->count() }}</h3>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Próximos Jogos - NOVO DESIGN -->
    <div class="card mb-4">
        <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Próximos Jogos</h5>
        </div>
        <div class="card-body">
            <div class="row g-3">
                @php
                $upcomingDraws = App\Models\Draw::where('draw_date', '>', now())
                    ->where('is_completed', false)
                    ->get();
                $totobolaGame = App\Models\Game::where('name', 'Totobola')->first();
                @endphp
                @foreach($upcomingDraws as $draw)
                <div class="col-md-6 col-lg-4">
                    <div class="card border-0 shadow h-100">
                        <div class="card-body d-flex flex-column">
                            <div class="d-flex align-items-center mb-2">
                                <span class="badge bg-primary me-2" style="font-size:1.2rem;">
                                    <i class="fas fa-futbol"></i>
                                </span>
                                <h5 class="mb-0 flex-grow-1">{{ $draw->game->name }}</h5>
                            </div>
                            <div class="mb-2">
                                <span class="text-muted small">Data:</span>
                                <span class="fw-bold">{{ $draw->draw_date->format('d/m/Y - H:i') }}</span>
                            </div>
                            <div class="mb-2">
                                <span class="text-muted small">Jackpot:</span>
                                <span class="fw-bold text-success">€{{ number_format($draw->jackpot_amount, 2) }}</span>
                            </div>
                            <div class="mb-3">
                                <span class="text-muted small">Grupos:</span>
                                <span class="fw-bold">{{ auth()->user()->groups()->where('game_id', $draw->game_id)->count() }} grupo(s)</span>
                            </div>
                            <div class="d-flex gap-2 mt-auto">
                                <a href="{{ route('games.show', $draw->game) }}" class="btn btn-outline-primary w-100">
                                    <i class="fas fa-eye me-1"></i> Ver Jogo
                                </a>
                                <a href="{{ route('groups.index', ['game_id' => $draw->game_id]) }}" class="btn btn-primary w-100">
                                    <i class="fas fa-users me-1"></i> Ver Grupos
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
                @if($totobolaGame)
                <div class="col-md-6 col-lg-4">
                    <div class="card border-0 shadow h-100">
                        <div class="card-body d-flex flex-column">
                            <div class="d-flex align-items-center mb-2">
                                <span class="badge bg-warning me-2" style="font-size:1.2rem;">
                                    <i class="fas fa-star"></i>
                                </span>
                                <h5 class="mb-0 flex-grow-1">{{ $totobolaGame->name }}</h5>
                            </div>
                            <div class="mb-2 text-muted small">Data: -</div>
                            <div class="mb-2 text-muted small">Jackpot: -</div>
                            <div class="mb-3">
                                <span class="text-muted small">Grupos:</span>
                                <span class="fw-bold">{{ auth()->user()->groups()->where('game_id', $totobolaGame->id)->count() }} grupo(s)</span>
                            </div>
                            <div class="d-flex gap-2 mt-auto">
                                <a href="{{ route('games.show', $totobolaGame) }}" class="btn btn-outline-primary w-100">
                                    <i class="fas fa-eye me-1"></i> Ver Jogo
                                </a>
                                <a href="{{ route('groups.index', ['game_id' => $totobolaGame->id]) }}" class="btn btn-primary w-100">
                                    <i class="fas fa-users me-1"></i> Ver Grupos
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Meus Grupos -->
    <div class="card">
        <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Meus Grupos</h5>
            <a href="{{ route('groups.index') }}" class="btn btn-sm btn-primary">Ver Todos</a>
        </div>
        <div class="card-body">
            <div class="row g-4">
                @forelse(Auth::user()->groups as $group)
                <div class="col-md-4">
                    <div class="card h-100">
                        <div class="card-body">
                            <div class="row mb-2">
                                <div class="col-8">
                                    <h5 class="card-title mb-0">{{ $group->name }}</h5>
                                    <div class="small text-muted mt-1">Administrador <b>{{ $group->admin->name ?? '' }}</b></div>
                                </div>
                                <div class="col-4 text-end">
                                    <span class="badge bg-primary">{{ $group->game->name }}</span>
                                </div>
                            </div>
                            <div class="row mb-2">
                                <div class="col-4">
                                    <span class="small">Membros<br><b>{{ $group->members()->count() }}/{{ $group->max_members ?? '-' }}</b></span>
                                </div>
                                <div class="col-8">
                                    <span class="small">Localização<br><b>{{ $group->location ?? '-' }}</b></span>
                                </div>
                            </div>
                            <div class="row mb-2">
                                <div class="col-12">
                                    <span class="badge bg-success">Público</span>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12 text-end">
                                    <a href="{{ route('groups.show', $group) }}" class="btn btn-outline-primary"><i class="fas fa-eye me-1"></i> Ver</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @empty
                <div class="col-12">
                    <div class="alert alert-info text-center">
                        <p class="mb-0">Você ainda não participa de nenhum grupo.</p>
                        <a href="{{ route('groups.index') }}" class="btn btn-primary mt-2">Encontrar Grupos</a>
                    </div>
                </div>
                @endforelse
            </div>
        </div>
    </div>
</div>
<style>
    .btn.btn-outline-primary.w-100 {
        min-width: 160px;
        max-width: 260px;
        min-height: 40px;
        font-size: 15px;
        box-sizing: border-box;
    }
    .dropdown-menu.w-100 {
        min-width: 100% !important;
    }
</style>
@endsection
