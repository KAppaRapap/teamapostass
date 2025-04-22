@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0">Minhas Apostas</h2>
        <div class="dropdown">
            <button class="btn btn-primary dropdown-toggle" type="button" id="createBetDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                <i class="fas fa-plus me-1"></i> Nova Aposta
            </button>
            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="createBetDropdown">
                @foreach(Auth::user()->groups as $userGroup)
                <li><a class="dropdown-item" href="{{ route('betting-slips.create', $userGroup) }}">{{ $userGroup->name }} ({{ $userGroup->game->name }})</a></li>
                @endforeach
                @if(Auth::user()->groups->isEmpty())
                <li><span class="dropdown-item text-muted">Você precisa participar de um grupo primeiro</span></li>
                <li><hr class="dropdown-divider"></li>
                <li><a class="dropdown-item" href="{{ route('groups.index') }}">Ver Grupos Disponíveis</a></li>
                @endif
            </ul>
        </div>
    </div>

    @if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
    @endif

    <div class="card mb-4">
        <div class="card-header bg-white py-3">
            <h5 class="mb-0">Apostas Ativas</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Jogo</th>
                            <th>Grupo</th>
                            <th>Data do Sorteio</th>
                            <th>Números</th>
                            <th>Status</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse(Auth::user()->bettingSlips()->with(['draw.game', 'group'])->where('is_checked', false)->orderBy('created_at', 'desc')->get() as $slip)
                        <tr>
                            <td>{{ $slip->draw->game->name }}</td>
                            <td>
                                @if($slip->group)
                                <a href="{{ route('groups.show', $slip->group) }}">{{ $slip->group->name }}</a>
                                @else
                                <span class="text-muted">Individual</span>
                                @endif
                            </td>
                            <td>{{ $slip->draw->draw_date->format('d/m/Y H:i') }}</td>
                            <td>{{ $slip->numbers }}</td>
                            <td>
                                @if($slip->draw->is_completed)
                                <span class="badge bg-danger">Não Premiado</span>
                                @else
                                <span class="badge bg-warning">Pendente</span>
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('betting-slips.show', $slip) }}" class="btn btn-sm btn-outline-primary">
                                    <i class="fas fa-eye"></i>
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center py-4">
                                <p class="mb-0">Você não tem apostas ativas no momento.</p>
                                @if(!Auth::user()->groups->isEmpty())
                                <div class="dropdown mt-2">
                                    <button class="btn btn-primary dropdown-toggle" type="button" id="createBetDropdown2" data-bs-toggle="dropdown" aria-expanded="false">
                                        <i class="fas fa-plus me-1"></i> Criar Nova Aposta
                                    </button>
                                    <ul class="dropdown-menu" aria-labelledby="createBetDropdown2">
                                        @foreach(Auth::user()->groups as $userGroup)
                                        <li><a class="dropdown-item" href="{{ route('betting-slips.create', $userGroup) }}">{{ $userGroup->name }} ({{ $userGroup->game->name }})</a></li>
                                        @endforeach
                                    </ul>
                                </div>
                                @else
                                <a href="{{ route('groups.index') }}" class="btn btn-primary mt-2">
                                    <i class="fas fa-users me-1"></i> Participar de um Grupo
                                </a>
                                @endif
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Apostas Não Premiadas -->
    <div class="card mb-4">
        <div class="card-header bg-white py-3">
            <h5 class="mb-0">Apostas Não Premiadas</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Jogo</th>
                            <th>Grupo</th>
                            <th>Data do Sorteio</th>
                            <th>Números</th>
                            <th>Status</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse(Auth::user()->bettingSlips()->with(['draw.game','group'])->where('has_won', false)->where('is_checked', true)->orderBy('created_at','desc')->get() as $slip)
                        <tr>
                            <td>{{ $slip->draw->game->name }}</td>
                            <td>
                                @if($slip->group)
                                    <a href="{{ route('groups.show', $slip->group) }}">{{ $slip->group->name }}</a>
                                @else
                                    <span class="text-muted">Individual</span>
                                @endif
                            </td>
                            <td>{{ $slip->draw->draw_date->format('d/m/Y H:i') }}</td>
                            <td>{{ $slip->numbers }}</td>
                            <td><span class="badge bg-danger">Sem Prêmio</span></td>
                            <td>
                                <a href="{{ route('betting-slips.show', $slip) }}" class="btn btn-sm btn-outline-primary">
                                    <i class="fas fa-eye"></i>
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center py-4">Você não tem apostas não premiadas.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header bg-white py-3">
            <h5 class="mb-0">Apostas Premiadas</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Jogo</th>
                            <th>Grupo</th>
                            <th>Data do Sorteio</th>
                            <th>Números</th>
                            <th>Prêmio</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse(Auth::user()->bettingSlips()->with(['draw.game', 'group'])->where('has_won', true)->orderBy('created_at', 'desc')->get() as $slip)
                        <tr>
                            <td>{{ $slip->draw->game->name }}</td>
                            <td>
                                @if($slip->group)
                                <a href="{{ route('groups.show', $slip->group) }}">{{ $slip->group->name }}</a>
                                @else
                                <span class="text-muted">Individual</span>
                                @endif
                            </td>
                            <td>{{ $slip->draw->draw_date->format('d/m/Y') }}</td>
                            <td>{{ $slip->numbers }}</td>
                            <td class="text-success fw-bold">€{{ number_format($slip->prize_amount, 2) }}</td>
                            <td>
                                <a href="{{ route('betting-slips.show', $slip) }}" class="btn btn-sm btn-outline-primary">
                                    <i class="fas fa-eye"></i>
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center py-4">
                                <p class="mb-0">Você ainda não tem apostas premiadas.</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
