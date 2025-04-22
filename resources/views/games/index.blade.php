@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0">Jogos Disponíveis</h2>
        @if(auth()->user()->is_admin)
        <a href="{{ route('games.create') }}" class="btn btn-primary">
            <i class="fas fa-plus me-1"></i> Novo Jogo
        </a>
        @endif
    </div>

    @if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
    @endif

    <div class="row g-4">
        @forelse($games as $game)
        <div class="col-md-6 col-lg-4">
            <div class="card h-100 game-card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h4 class="card-title mb-0">{{ $game->name }}</h4>
                        <span class="badge bg-primary">{{ $game->type }}</span>
                    </div>
                    <p class="card-text text-muted">{{ Str::limit($game->description, 100) }}</p>
                    <div class="d-flex justify-content-between align-items-center mt-3">
                        <span class="text-primary fw-bold">€{{ number_format($game->price_per_bet, 2) }} / aposta</span>
                        <a href="{{ route('games.show', $game) }}" class="btn btn-outline-primary">Ver Detalhes</a>
                    </div>
                </div>
            </div>
        </div>
        @empty
        <div class="col-12">
            <div class="alert alert-info">
                Não há jogos disponíveis no momento.
            </div>
        </div>
        @endforelse
    </div>
</div>
@endsection
