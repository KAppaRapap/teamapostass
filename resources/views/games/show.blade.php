@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0">{{ $game->name }}</h2>
        <div>
            @if(auth()->user()->is_admin)
            <a href="{{ route('games.edit', $game) }}" class="btn btn-outline-primary me-2">
                <i class="fas fa-edit me-1"></i> Editar
            </a>
            <form action="{{ route('games.destroy', $game) }}" method="POST" class="d-inline">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-outline-danger" onclick="return confirm('Tem certeza que deseja excluir este jogo?')">
                    <i class="fas fa-trash me-1"></i> Excluir
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

    <div class="row">
        <div class="col-lg-8">
            <div class="card mb-4">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h4>Detalhes do Jogo</h4>
                        <span class="badge bg-primary">{{ $game->type }}</span>
                    </div>
                    
                    <div class="mb-4">
                        <h5>Descrição</h5>
                        <p class="text-muted">{{ $game->description ?: 'Nenhuma descrição disponível.' }}</p>
                    </div>
                    
                    <div class="mb-4">
                        <h5>Regras</h5>
                        <div class="text-muted">
                            {!! $game->rules ? nl2br(e($game->rules)) : 'Nenhuma regra específica disponível.' !!}
                        </div>
                    </div>
                    
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="mb-0">Preço por Aposta</h5>
                            <p class="text-primary fw-bold mb-0">€{{ number_format($game->price_per_bet, 2) }}</p>
                        </div>
                        <a href="{{ route('groups.index', ['game_id' => $game->id]) }}" class="btn btn-primary">
                            <i class="fas fa-users me-1"></i> Ver Grupos
                        </a>
                    </div>
                </div>
            </div>
            
            @if($nextDraw)
            <div class="card mb-4">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0">Próximo Sorteio</h5>
                </div>
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="mb-1"><strong>Data:</strong> {{ $nextDraw->draw_date->format('d/m/Y H:i') }}</p>
                            <p class="mb-1"><strong>Jackpot:</strong> €{{ number_format($nextDraw->jackpot_amount, 2) }}</p>
                            <p class="mb-0"><strong>Status:</strong> 
                                <span class="badge bg-warning">Aguardando Sorteio</span>
                            </p>
                        </div>
                        <a href="#" class="btn btn-outline-primary">
                            <i class="fas fa-ticket-alt me-1"></i> Apostar
                        </a>
                    </div>
                </div>
            </div>
            @endif
            
            @if($latestDraw)
            <div class="card">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0">Último Resultado</h5>
                </div>
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="mb-1"><strong>Data:</strong> {{ $latestDraw->draw_date->format('d/m/Y H:i') }}</p>
                            <p class="mb-1"><strong>Números Sorteados:</strong> {{ is_array($latestDraw->winning_numbers) ? implode(', ', $latestDraw->winning_numbers) : $latestDraw->winning_numbers }}</p>
                            <p class="mb-0"><strong>Prêmio:</strong> €{{ number_format($latestDraw->jackpot_amount, 2) }}</p>
                        </div>
                        <a href="#" class="btn btn-outline-primary">
                            <i class="fas fa-search me-1"></i> Ver Detalhes
                        </a>
                    </div>
                </div>
            </div>
            @endif
        </div>
        
        <div class="col-lg-4">
            <div class="card mb-4">
                <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Grupos Populares</h5>
                    <a href="{{ route('groups.index', ['game_id' => $game->id]) }}" class="btn btn-sm btn-primary">Ver Todos</a>
                </div>
                <div class="card-body">
                    @if($groups->count() > 0)
                    <div class="list-group list-group-flush">
                        @foreach($groups as $group)
                        <a href="{{ route('groups.show', $group) }}" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="mb-1">{{ $group->name }}</h6>
                                <small class="text-muted">{{ $group->members_count ?? 0 }} membros</small>
                            </div>
                            <span class="badge bg-primary rounded-pill">
                                <i class="fas fa-users"></i>
                            </span>
                        </a>
                        @endforeach
                    </div>
                    @else
                    <div class="text-center py-3">
                        <p class="mb-0">Nenhum grupo disponível para este jogo.</p>
                        <a href="{{ route('groups.create') }}" class="btn btn-primary mt-2">
                            <i class="fas fa-plus me-1"></i> Criar Grupo
                        </a>
                    </div>
                    @endif
                </div>
            </div>
            
            <div class="card">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0">Estatísticas</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <h6>Números Mais Frequentes</h6>
                        <div class="d-flex flex-wrap gap-2">
                            <span class="badge bg-primary rounded-pill p-2">12</span>
                            <span class="badge bg-primary rounded-pill p-2">23</span>
                            <span class="badge bg-primary rounded-pill p-2">34</span>
                            <span class="badge bg-primary rounded-pill p-2">45</span>
                            <span class="badge bg-primary rounded-pill p-2">5</span>
                        </div>
                    </div>
                    <div>
                        <h6>Números Menos Frequentes</h6>
                        <div class="d-flex flex-wrap gap-2">
                            <span class="badge bg-secondary rounded-pill p-2">7</span>
                            <span class="badge bg-secondary rounded-pill p-2">18</span>
                            <span class="badge bg-secondary rounded-pill p-2">29</span>
                            <span class="badge bg-secondary rounded-pill p-2">40</span>
                            <span class="badge bg-secondary rounded-pill p-2">49</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
