@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0">Próximos Jogos</h2>
        <a href="{{ route('dashboard') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-1"></i> Voltar ao Dashboard
        </a>
    </div>

    @if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
    @endif

    <div class="card">
        <div class="card-body">
            @if($upcomingDraws->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover">
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
                        @foreach($upcomingDraws as $draw)
                        <tr>
                            <td>
                                <div class="d-flex align-items-center">
                                    <span class="fw-bold">{{ $draw->game->name }}</span>
                                    <span class="badge bg-primary ms-2">{{ $draw->game->type }}</span>
                                </div>
                            </td>
                            <td>{{ $draw->draw_date->format('d/m/Y H:i') }}</td>
                            <td>€{{ number_format($draw->jackpot_amount, 2) }}</td>
                            <td>
                                @php
                                $userGroups = auth()->user()->groups()->where('game_id', $draw->game_id)->count();
                                @endphp
                                {{ $userGroups }} {{ $userGroups == 1 ? 'grupo' : 'grupos' }}
                            </td>
                            <td>
                                <div class="btn-group">
                                    <a href="{{ route('games.show', $draw->game) }}" class="btn btn-sm btn-outline-primary">
                                        <i class="fas fa-eye me-1"></i> Ver Jogo
                                    </a>
                                    <a href="{{ route('groups.index', ['game_id' => $draw->game_id]) }}" class="btn btn-sm btn-primary">
                                        <i class="fas fa-users me-1"></i> Ver Grupos
                                    </a>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                        @php $totobolaGame = App\Models\Game::where('name', 'Totobola')->first(); @endphp
                        @if($totobolaGame)
                        <tr>
                            <td>
                                <div class="d-flex align-items-center">
                                    <span class="fw-bold">{{ $totobolaGame->name }}</span>
                                    <span class="badge bg-primary ms-2">{{ $totobolaGame->type }}</span>
                                </div>
                            </td>
                            <td>-</td>
                            <td>-</td>
                            <td>
                                @php $userGroups = auth()->user()->groups()->where('game_id', $totobolaGame->id)->count(); @endphp
                                {{ $userGroups }} {{ $userGroups == 1 ? 'grupo' : 'grupos' }}
                            </td>
                            <td>
                                <div class="btn-group">
                                    <a href="{{ route('games.show', $totobolaGame) }}" class="btn btn-sm btn-outline-primary">
                                        <i class="fas fa-eye me-1"></i> Ver Jogo
                                    </a>
                                    <a href="{{ route('groups.index', ['game_id' => $totobolaGame->id]) }}" class="btn btn-sm btn-primary">
                                        <i class="fas fa-users me-1"></i> Ver Grupos
                                    </a>
                                </div>
                            </td>
                        </tr>
                        @endif
                    </tbody>
                </table>
            </div>
            <div class="mt-3">
                {{ $upcomingDraws->links('groups.pagination') }}
            </div>
            @else
            <div class="text-center py-5">
                <i class="fas fa-calendar-times fa-4x text-muted mb-3"></i>
                <h4>Nenhum jogo agendado</h4>
                <p class="text-muted">Não há jogos agendados para os próximos dias.</p>
                <a href="{{ route('games.index') }}" class="btn btn-primary mt-2">
                    <i class="fas fa-gamepad me-1"></i> Ver Jogos Disponíveis
                </a>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
