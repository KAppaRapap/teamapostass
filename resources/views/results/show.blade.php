@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0">Detalhes do Resultado</h2>
        <a href="{{ route('results.index') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-1"></i> Voltar aos Resultados
        </a>
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

    <div class="row">
        <div class="col-lg-8">
            <!-- Detalhes do Sorteio -->
            <div class="card mb-4">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0">{{ $draw->game->name }} - {{ $draw->draw_date->format('d/m/Y') }}</h5>
                </div>
                <div class="card-body">
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <h6>Data do Sorteio</h6>
                            <p class="text-muted">{{ $draw->draw_date->format('d/m/Y H:i') }}</p>
                        </div>
                        <div class="col-md-6">
                            <h6>Jackpot</h6>
                            <p class="text-muted">
                                @if($draw->jackpot_amount > 0)
                                    €{{ number_format($draw->jackpot_amount, 2) }}
                                @else
                                    <span class="text-muted">Sem jackpot</span>
                                @endif
                            </p>
                        </div>
                    </div>

                    <div class="mb-4">
                        <h6>Números Sorteados</h6>
                        <div class="d-flex flex-wrap gap-2 mt-2">
                            @if($draw->game->name == 'Euromilhões')
                                @if(isset($draw->winning_numbers['numbers']) && is_array($draw->winning_numbers['numbers']))
                                    @foreach($draw->winning_numbers['numbers'] as $number)
                                        <span class="badge rounded-pill bg-primary p-2">{{ $number }}</span>
                                    @endforeach
                                @endif
                                @if(isset($draw->winning_numbers['stars']) && is_array($draw->winning_numbers['stars']))
                                    @foreach($draw->winning_numbers['stars'] as $star)
                                        <span class="badge rounded-pill bg-warning p-2"><i class="fas fa-star me-1"></i>{{ $star }}</span>
                                    @endforeach
                                @endif
                            @elseif($draw->game->name == 'Totoloto')
                                @if(is_array($draw->winning_numbers))
                                    @foreach($draw->winning_numbers as $number)
                                        <span class="badge rounded-pill bg-primary p-2">{{ $number }}</span>
                                    @endforeach
                                @endif
                            @elseif($draw->game->name == 'Totobola')
                                @if(is_array($draw->winning_numbers))
                                    @foreach($draw->winning_numbers as $result)
                                        <span class="badge rounded-pill bg-info p-2">{{ $result }}</span>
                                    @endforeach
                                @endif
                            @elseif($draw->game->name == 'Placard')
                                @if(isset($draw->winning_numbers['message']))
                                    <span>{{ $draw->winning_numbers['message'] }}</span>
                                @else
                                    {{-- Fallback if Placard has a different array structure --}}
                                    <span>{{ implode(', ', (array) $draw->winning_numbers) }}</span>
                                @endif
                            @else
                                {{-- Fallback for unknown game types or simple arrays --}}
                                @if(is_array($draw->winning_numbers))
                                    <span>{{ implode(', ', $draw->winning_numbers) }}</span>
                                @else
                                    <span>{{ $draw->winning_numbers }}</span>
                                @endif
                            @endif
                        </div>
                    </div>

                    @if($draw->game->name == 'Totobola' || $draw->game->name == 'Placard')
                    <div class="mb-4">
                        <h6>Detalhes dos Jogos</h6>
                        <p class="text-muted">{{ $draw->draw_number ?: 'Informações não disponíveis' }}</p>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Suas Apostas -->
            @if(Auth::check() && $userBettingSlips->count() > 0)
            <div class="card mb-4">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0">Suas Apostas</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Grupo</th>
                                    <th>Números</th>
                                    <th>Resultado</th>
                                    <th>Prêmio</th>
                                    <th>Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($userBettingSlips as $slip)
                                <tr>
                                    <td>
                                        @if($slip->group)
                                        <a href="{{ route('groups.show', $slip->group) }}">{{ $slip->group->name }}</a>
                                        @else
                                        <span class="text-muted">Individual</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="d-flex flex-wrap gap-1">
                                            @if($draw->game->name == 'Euromilhões')
                                                @if(isset($slip->numbers['numbers']) && is_array($slip->numbers['numbers']))
                                                    @foreach($slip->numbers['numbers'] as $number)
                                                        <span class="badge rounded-pill bg-secondary p-2">{{ $number }}</span>
                                                    @endforeach
                                                @endif
                                                @if(isset($slip->numbers['stars']) && is_array($slip->numbers['stars']))
                                                    @foreach($slip->numbers['stars'] as $star)
                                                        <span class="badge rounded-pill bg-secondary p-2"><i class="fas fa-star"></i>{{ $star }}</span>
                                                    @endforeach
                                                @endif
                                            @else
                                                @if(is_array($slip->numbers))
                                                    @foreach($slip->numbers as $number)
                                                        <span class="badge rounded-pill bg-secondary p-2">{{ $number }}</span>
                                                    @endforeach
                                                @else
                                                    <span>{{ $slip->numbers }}</span>
                                                @endif
                                            @endif
                                        </div>
                                    </td>
                                    <td>
                                        @if($slip->has_won)
                                        <span class="badge bg-success">Premiado</span>
                                        @elseif($slip->is_checked)
                                        <span class="badge bg-danger">Não Premiado</span>
                                        @else
                                        <span class="badge bg-warning">Pendente</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($slip->prize_amount > 0)
                                        <span class="text-success fw-bold">€{{ number_format($slip->prize_amount, 2) }}</span>
                                        @else
                                        <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ route('betting-slips.show', $slip) }}" class="btn btn-sm btn-outline-primary">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        @if(!$slip->is_checked)
                                        <form action="{{ route('betting-slips.check-results', $slip) }}" method="POST" class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-outline-success">
                                                <i class="fas fa-check"></i>
                                            </button>
                                        </form>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            @elseif(Auth::check())
            <div class="card mb-4">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0">Suas Apostas</h5>
                </div>
                <div class="card-body">
                    <p class="text-center mb-0">Você não tem apostas para este sorteio.</p>
                </div>
            </div>
            @endif

            <!-- Estatísticas -->
            <div class="card">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0">Estatísticas do Sorteio</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <h6>Distribuição de Prêmios</h6>
                                <ul class="list-group list-group-flush">
                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                        1º Prêmio
                                        <span class="badge bg-success rounded-pill">€{{ number_format($draw->jackpot_amount, 2) }}</span>
                                    </li>
                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                        2º Prêmio
                                        <span class="badge bg-success rounded-pill">€{{ number_format($draw->jackpot_amount * 0.1, 2) }}</span>
                                    </li>
                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                        3º Prêmio
                                        <span class="badge bg-success rounded-pill">€{{ number_format($draw->jackpot_amount * 0.05, 2) }}</span>
                                    </li>
                                </ul>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <h6>Apostas Premiadas</h6>
                                <div class="d-flex justify-content-between mb-2">
                                    <span>Total de Apostas:</span>
                                    <span class="fw-bold">{{ $draw->bettingSlips()->count() }}</span>
                                </div>
                                <div class="d-flex justify-content-between mb-2">
                                    <span>Apostas Premiadas:</span>
                                    <span class="fw-bold text-success">{{ $draw->bettingSlips()->where('has_won', true)->count() }}</span>
                                </div>
                                <div class="d-flex justify-content-between">
                                    <span>Total Distribuído:</span>
                                    <span class="fw-bold text-success">€{{ number_format($draw->bettingSlips()->sum('prize_amount'), 2) }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <!-- Próximos Sorteios -->
            <div class="card mb-4">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0">Próximos Sorteios</h5>
                </div>
                <div class="card-body">
                    @php
                    $upcomingDraws = App\Models\Draw::where('game_id', $draw->game_id)
                        ->where('draw_date', '>', now())
                        ->where('is_completed', false)
                        ->orderBy('draw_date', 'asc')
                        ->take(3)
                        ->get();
                    @endphp

                    @if($upcomingDraws->count() > 0)
                    <ul class="list-group list-group-flush">
                        @foreach($upcomingDraws as $upcomingDraw)
                        <li class="list-group-item">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <strong>{{ $upcomingDraw->draw_date->format('d/m/Y H:i') }}</strong>
                                    <div class="text-muted">Jackpot: €{{ number_format($upcomingDraw->jackpot_amount, 2) }}</div>
                                </div>
                                @if(Auth::check())
                                <div class="dropdown">
                                    <button class="btn btn-sm btn-outline-primary dropdown-toggle" type="button" id="dropdownMenuButton{{ $upcomingDraw->id }}" data-bs-toggle="dropdown" aria-expanded="false">
                                        Apostar
                                    </button>
                                    <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton{{ $upcomingDraw->id }}">
                                        @foreach(Auth::user()->groups()->where('game_id', $draw->game_id)->get() as $group)
                                        <li><a class="dropdown-item" href="{{ route('betting-slips.create', $group) }}">{{ $group->name }}</a></li>
                                        @endforeach
                                        @if(Auth::user()->groups()->where('game_id', $draw->game_id)->count() == 0)
                                        <li><span class="dropdown-item text-muted">Você precisa participar de um grupo primeiro</span></li>
                                        <li><hr class="dropdown-divider"></li>
                                        <li><a class="dropdown-item" href="{{ route('groups.index') }}">Ver Grupos Disponíveis</a></li>
                                        @endif
                                    </ul>
                                </div>
                                @endif
                            </div>
                        </li>
                        @endforeach
                    </ul>
                    @else
                    <p class="text-center mb-0">Não há sorteios agendados para este jogo.</p>
                    @endif
                </div>
            </div>

            <!-- Histórico de Resultados -->
            <div class="card">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0">Histórico de Resultados</h5>
                </div>
                <div class="card-body">
                    @php
                    $pastDraws = App\Models\Draw::where('game_id', $draw->game_id)
                        ->where('id', '!=', $draw->id)
                        ->where('is_completed', true)
                        ->orderBy('draw_date', 'desc')
                        ->take(5)
                        ->get();
                    @endphp

                    @if($pastDraws->count() > 0)
                    <ul class="list-group list-group-flush">
                        @foreach($pastDraws as $pastDraw)
                        <li class="list-group-item">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <strong>{{ $pastDraw->draw_date->format('d/m/Y') }}</strong>
                                    <div>
                                        <small class="text-muted">Números:
                                            @if($pastDraw->game->name == 'Euromilhões')
                                                @if(isset($pastDraw->winning_numbers['numbers']) && is_array($pastDraw->winning_numbers['numbers']))
                                                    {{ implode(', ', $pastDraw->winning_numbers['numbers']) }}
                                                @endif
                                                @if(isset($pastDraw->winning_numbers['stars']) && is_array($pastDraw->winning_numbers['stars']))
                                                    <i class="fas fa-star"></i> {{ implode(', ', $pastDraw->winning_numbers['stars']) }}
                                                @endif
                                            @else
                                                @if(is_array($pastDraw->winning_numbers))
                                                    {{ implode(', ', $pastDraw->winning_numbers) }}
                                                @else
                                                    {{ $pastDraw->winning_numbers }}
                                                @endif
                                            @endif
                                        </small>
                                    </div>
                                </div>
                                <a href="{{ route('results.show', $pastDraw) }}" class="btn btn-sm btn-outline-secondary">
                                    <i class="fas fa-eye"></i>
                                </a>
                            </div>
                        </li>
                        @endforeach
                    </ul>
                    <div class="text-center mt-3">
                        <a href="{{ route('results.index') }}" class="btn btn-outline-primary btn-sm">Ver Todos</a>
                    </div>
                    @else
                    <p class="text-center mb-0">Não há resultados anteriores para este jogo.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
