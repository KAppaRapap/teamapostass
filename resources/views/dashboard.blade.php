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
                        <h6 class="text-muted mb-1">Próximos Sorteios</h6>
                        <h3 class="mb-0">{{ App\Models\Draw::where('draw_date', '>', now())->where('is_completed', false)->count() }}</h3>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Próximos Sorteios -->
    <div class="card mb-4">
        <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Próximos Sorteios</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Jogo</th>
                            <th>Data</th>
                            <th>Jackpot</th>
                            <th>Grupos</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                        $upcomingDraws = App\Models\Draw::where('draw_date', '>', now())
                            ->where('is_completed', false)
                            ->with(['game', 'bettingSlips'])
                            ->orderBy('draw_date', 'asc')
                            ->take(5)
                            ->get();
                        @endphp
                        
                        @forelse($upcomingDraws as $draw)
                        <tr>
                            <td>{{ $draw->game->name }}</td>
                            <td>
                                @php $end = $draw->draw_date->copy()->addHour(); @endphp
                                {{ $end->format('d/m/Y - H:i') }}<br>
                                <span class="text-muted small" id="countdown-{{ $draw->id }}"></span>
                                <script>
                                    document.addEventListener('DOMContentLoaded', function() {
                                        var endTime = new Date(@json($end->format('Y-m-d H:i:s')));
                                        var countdownElem = document.getElementById('countdown-{{ $draw->id }}');
                                        function updateCountdown() {
                                            var now = new Date();
                                            var diff = endTime - now;
                                            if (diff > 0) {
                                                var hours = Math.floor(diff / 1000 / 60 / 60);
                                                var minutes = Math.floor((diff / 1000 / 60) % 60);
                                                var seconds = Math.floor((diff / 1000) % 60);
                                                countdownElem.textContent = `Termina em ${hours}h ${minutes}m ${seconds}s`;
                                            } else {
                                                countdownElem.textContent = 'Sorteio encerrado';
                                            }
                                        }
                                        updateCountdown();
                                        setInterval(updateCountdown, 1000);
                                    });
                                </script>
                            </td>
                            <td>
                                @if(($draw->jackpot_amount ?? 0) > 0)
                                    €{{ number_format($draw->jackpot_amount, 2) }}
                                @else
                                    <span class="text-muted">Sem jackpot</span>
                                @endif
                            </td>
                            <td>
                                @php
                                $userGroups = Auth::user()->groups()->where('game_id', $draw->game_id)->count();
                                @endphp
                                
                                @if($userGroups > 0)
                                    {{ $userGroups }} {{ $userGroups == 1 ? 'grupo' : 'grupos' }}
                                @else
                                    Nenhum grupo
                                @endif
                            </td>
                            <td>
                                @if($userGroups > 0)
                                <div class="dropdown">
                                    <button class="btn btn-sm btn-outline-primary w-100 dropdown-toggle"
                                            type="button"
                                            id="dropdownMenuButton{{ $draw->id }}"
                                            data-bs-toggle="dropdown"
                                            aria-expanded="false"
                                            style="min-width:160px;max-width:260px;min-height:40px;">
                                        Criar Aposta
                                    </button>
                                    <ul class="dropdown-menu w-100" aria-labelledby="dropdownMenuButton{{ $draw->id }}">
                                        @foreach(Auth::user()->groups()->where('game_id', $draw->game_id)->get() as $group)
                                        <li><a class="dropdown-item" href="{{ route('betting-slips.create', $group) }}">{{ $group->name }}</a></li>
                                        @endforeach
                                    </ul>
                                </div>
                                @else
                                <a href="{{ route('groups.index') }}?game_id={{ $draw->game_id }}" class="btn btn-sm btn-outline-primary w-100" style="min-width:160px;max-width:260px;min-height:40px;">
                                    Encontrar Grupos
                                </a>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center py-3">Não há sorteios futuros disponíveis no momento.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
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
