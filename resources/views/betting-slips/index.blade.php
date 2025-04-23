@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0">Jogos Disponíveis</h2>
        <a href="{{ route('games.index') }}" class="btn btn-primary">
            <i class="fas fa-list me-1"></i> Ver Todos os Jogos
        </a>
    </div>
    <div class="card mb-4">
        <div class="card-header bg-white py-3">
            <h5 class="mb-0">Lista de Jogos</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Nome do Jogo</th>
                            <th>Tipo</th>
                            <th>Descrição</th>
                            <th>Preço por Aposta</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach(App\Models\Game::all() as $game)
                        <tr>
                            <td>{{ $game->name }}</td>
                            <td>{{ $game->type }}</td>
                            <td>{{ $game->description }}</td>
                            <td>€{{ number_format($game->price_per_bet, 2) }}</td>
                            <td>
                                <a href="{{ route('groups.index', ['game_id' => $game->id]) }}" class="btn btn-sm btn-outline-primary">
                                    <i class="fas fa-users me-1"></i> Ver Grupos
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
