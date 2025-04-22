@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0">{{ $group->name }}</h2>
        <div>
            @if($userIsAdmin)
            <a href="{{ route('groups.edit', $group) }}" class="btn btn-outline-primary me-2">
                <i class="fas fa-edit me-1"></i> Editar
            </a>
            <form action="{{ route('groups.destroy', $group) }}" method="POST" class="d-inline">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-outline-danger" onclick="return confirm('Tem certeza que deseja excluir este grupo?')">
                    <i class="fas fa-trash me-1"></i> Excluir
                </button>
            </form>
            @elseif($userIsMember)
            <form action="{{ route('groups.leave', $group) }}" method="POST" class="d-inline">
                @csrf
                <button type="submit" class="btn btn-outline-danger" onclick="return confirm('Tem certeza que deseja sair deste grupo?')">
                    <i class="fas fa-sign-out-alt me-1"></i> Sair do Grupo
                </button>
            </form>
            @else
            <form action="{{ route('groups.join', $group) }}" method="POST" class="d-inline">
                @csrf
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-sign-in-alt me-1"></i> Entrar no Grupo
                </button>
            </form>
            @endif
        </div>
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
            <div class="card mb-4">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h4>Detalhes do Grupo</h4>
                        <div>
                            <span class="badge bg-primary me-2">{{ $group->game->name }}</span>
                            <span class="badge {{ $group->is_public ? 'bg-success' : 'bg-warning' }}">
                                {{ $group->is_public ? 'Público' : 'Privado' }}
                            </span>
                        </div>
                    </div>
                    
                    <div class="mb-4">
                        <h5>Descrição</h5>
                        <p class="text-muted">
                            @if(is_array($group->description))
                                {{ implode(', ', $group->description) }}
                            @else
                                {{ $group->description ?: 'Nenhuma descrição disponível.' }}
                            @endif
                        </p>
                    </div>
                    
                    <div class="row mb-4">
                        <div class="col-md-4">
                            <h5>Administrador</h5>
                            <p class="text-muted">{{ $group->admin->name }}</p>
                        </div>
                        <div class="col-md-4">
                            <h5>Membros</h5>
                            <p class="text-muted">{{ $members->total() }}/{{ $group->max_members ?: '∞' }}</p>
                        </div>
                        <div class="col-md-4">
                            <h5>Localização</h5>
                            <p class="text-muted">
                                @php
                                    $city = is_array($group->city) ? implode(', ', $group->city) : $group->city;
                                    $region = is_array($group->region) ? implode(', ', $group->region) : $group->region;
                                @endphp
                                {{ $city ? $city . ', ' . $region : 'Não especificada' }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Apostas do Grupo -->
            <div class="card mb-4">
                <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Apostas do Grupo</h5>
                    @if($userIsMember)
                    <a href="{{ route('betting-slips.create', $group) }}" class="btn btn-primary btn-sm">
                        <i class="fas fa-plus me-1"></i> Nova Aposta
                    </a>
                    @endif
                </div>
                <div class="card-body">
                    @if($bettingSlips->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Data</th>
                                    <th>Sorteio</th>
                                    <th>Números</th>
                                    <th>Status</th>
                                    <th>Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($bettingSlips as $slip)
                                <tr>
                                    <td>{{ $slip->created_at->format('d/m/Y') }}</td>
                                    <td>{{ $slip->draw->draw_date->format('d/m/Y') }}</td>
                                    <td>
                                        @if(is_array($slip->numbers))
                                            {{ implode(', ', $slip->numbers) }}
                                        @else
                                            {{ $slip->numbers }}
                                        @endif
                                    </td>
                                    <td>
                                        @if($slip->draw->is_completed)
                                            @if($slip->has_won)
                                            <span class="badge bg-success">Premiado</span>
                                            @else
                                            <span class="badge bg-danger">Não Premiado</span>
                                            @endif
                                        @else
                                        <span class="badge bg-warning">Pendente</span>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ route('betting-slips.show', $slip->id) }}" class="btn btn-sm btn-outline-primary">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-3">
                        {{ $bettingSlips->links('groups.pagination') }}
                    </div>
                    @else
                    <div class="text-center py-4">
                        <p class="mb-0">Este grupo ainda não tem apostas registradas.</p>
                        @if($userIsMember)
                        <a href="{{ route('betting-slips.create', $group) }}" class="btn btn-primary mt-2">
                            <i class="fas fa-plus me-1"></i> Criar Primeira Aposta
                        </a>
                        @endif
                    </div>
                    @endif
                </div>
            </div>
        </div>
        
        <div class="col-lg-4">
            <!-- Membros do Grupo -->
            <div class="card mb-4">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0">Membros do Grupo</h5>
                </div>
                <div class="card-body">
                    <ul class="list-group list-group-flush">
                        @foreach($members as $member)
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <div>
                                <span>{{ $member->name }}</span>
                                @if($group->admin_id === $member->id)
                                <span class="badge bg-primary ms-2">Admin</span>
                                @endif
                                @if($member->pivot->status === 'banned')
                                <span class="badge bg-danger ms-2">Banido</span>
                                @endif
                            </div>
                            @if($userIsAdmin && $group->admin_id !== $member->id)
                            <div class="dropdown">
                                <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" id="dropdownMenuButton{{ $member->id }}" data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="fas fa-ellipsis-v"></i>
                                </button>
                                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdownMenuButton{{ $member->id }}">
                                    @if($member->pivot->status === 'banned')
                                    <li>
                                        <form action="{{ route('groups.unban-user', [$group, $member]) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="dropdown-item">
                                                <i class="fas fa-user-check me-2"></i> Desbanir
                                            </button>
                                        </form>
                                    </li>
                                    @else
                                    <li>
                                        <form action="{{ route('groups.ban-user', [$group, $member]) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="dropdown-item">
                                                <i class="fas fa-user-slash me-2"></i> Banir
                                            </button>
                                        </form>
                                    </li>
                                    @endif
                                </ul>
                            </div>
                            @endif
                        </li>
                        @endforeach
                    </ul>
                    
                    <div class="mt-3">
                        {{ $members->links('groups.pagination') }}
                    </div>
                </div>
            </div>
            
            <!-- Próximo Sorteio -->
            <div class="card mb-4">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0">Próximo Sorteio</h5>
                </div>
                <div class="card-body">
                    @php
                    $nextDraw = $group->game->draws()
                        ->where('draw_date', '>', now())
                        ->where('is_completed', false)
                        ->orderBy('draw_date', 'asc')
                        ->first();
                    @endphp
                    
                    @if($nextDraw)
                    <div>
                        <p class="mb-1"><strong>Data:</strong> {{ $nextDraw->draw_date->format('d/m/Y H:i') }}</p>
                        <p class="mb-1"><strong>Jackpot:</strong> €{{ number_format($nextDraw->jackpot_amount, 2) }}</p>
                        <p class="mb-3"><strong>Status:</strong> 
                            <span class="badge bg-warning">Aguardando Sorteio</span>
                        </p>
                        
                        @if($userIsMember)
                        <div class="d-grid">
                            <a href="{{ route('betting-slips.create', $group) }}" class="btn btn-primary">
                                <i class="fas fa-ticket-alt me-1"></i> Criar Aposta para este Sorteio
                            </a>
                        </div>
                        @endif
                    </div>
                    @else
                    <p class="text-center mb-0">Não há sorteios agendados para este jogo.</p>
                    @endif
                </div>
            </div>
            
            <!-- Estatísticas do Grupo -->
            <div class="card">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0">Estatísticas</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <div class="d-flex justify-content-between mb-2">
                            <span>Total de Apostas:</span>
                            <span class="fw-bold">{{ $bettingSlips->total() }}</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span>Apostas Ganhadoras:</span>
                            <span class="fw-bold text-success">{{ $group->bettingSlips()->where('winnings', '>', 0)->count() }}</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span>Total Ganho:</span>
                            <span class="fw-bold text-success">€{{ number_format($group->bettingSlips()->sum('winnings'), 2) }}</span>
                        </div>
                        <div class="d-flex justify-content-between">
                            <span>Membros Ativos:</span>
                            <span class="fw-bold">{{ $group->members()->wherePivot('status', 'active')->count() }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
