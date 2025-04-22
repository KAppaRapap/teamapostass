@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0">Grupos de Apostadores</h2>
        <a href="{{ route('groups.create') }}" class="btn btn-primary">
            <i class="fas fa-plus me-1"></i> Novo Grupo
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

    <!-- Filtros -->
    <div class="card mb-4">
        <div class="card-body">
            <form action="{{ route('groups.index') }}" method="GET" class="row g-3">
                <div class="col-md-4">
                    <label for="game_id" class="form-label">Jogo</label>
                    <select class="form-select" id="game_id" name="game_id">
                        <option value="">Todos os jogos</option>
                        @foreach($games as $game)
                        <option value="{{ $game->id }}" {{ request('game_id') == $game->id ? 'selected' : '' }}>
                            {{ $game->name }}
                        </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    <label for="city" class="form-label">Cidade</label>
                    <input type="text" class="form-control" id="city" name="city" value="{{ request('city') }}" placeholder="Ex: Lisboa">
                </div>
                <div class="col-md-4">
                    <label for="region" class="form-label">Região</label>
                    <input type="text" class="form-control" id="region" name="region" value="{{ request('region') }}" placeholder="Ex: Norte">
                </div>
                <div class="col-12 text-end">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-filter me-1"></i> Filtrar
                    </button>
                    <a href="{{ route('groups.index') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-redo me-1"></i> Limpar
                    </a>
                </div>
            </form>
        </div>
    </div>

    <div class="row g-4">
        @forelse($groups as $group)
        <div class="col-md-6 col-lg-4">
            <div class="card h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="card-title mb-0">{{ $group->name }}</h5>
                        <span class="badge bg-primary">{{ $group->game->name }}</span>
                    </div>
                    <p class="card-text text-muted">{{ Str::limit($group->description, 100) }}</p>
                    
                    <div class="d-flex align-items-center mb-3">
                        <div class="me-3">
                            <small class="text-muted d-block">Administrador</small>
                            <span>{{ $group->admin->name }}</span>
                        </div>
                        <div class="me-3">
                            <small class="text-muted d-block">Membros</small>
                            <span>{{ $group->members->count() }}/{{ $group->max_members ?: '∞' }}</span>
                        </div>
                        <div>
                            <small class="text-muted d-block">Localização</small>
                            <span>{{ $group->city ?: 'N/A' }}</span>
                        </div>
                    </div>
                    
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="badge {{ $group->is_public ? 'bg-success' : 'bg-warning' }}">
                            {{ $group->is_public ? 'Público' : 'Privado' }}
                        </span>
                        <div>
                            <a href="{{ route('groups.show', $group) }}" class="btn btn-outline-primary">
                                <i class="fas fa-eye me-1"></i> Ver
                            </a>
                            @if(!$group->members->contains(auth()->user()))
                            <form action="{{ route('groups.join', $group) }}" method="POST" class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-primary ms-2">
                                    <i class="fas fa-sign-in-alt me-1"></i> Entrar
                                </button>
                            </form>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @empty
        <div class="col-12">
            <div class="alert alert-info">
                <p class="mb-0">Nenhum grupo encontrado com os filtros selecionados.</p>
                <p class="mb-0 mt-2">
                    <a href="{{ route('groups.create') }}" class="alert-link">Clique aqui</a> para criar um novo grupo.
                </p>
            </div>
        </div>
        @endforelse
    </div>

    <div class="mt-4">
        {{ $groups->withQueryString()->links('groups.pagination') }}
    </div>
</div>
@endsection
