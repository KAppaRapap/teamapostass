@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Editar Jogo</div>
                <div class="card-body">
                    <form action="{{ route('games.update', $game) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="mb-3">
                            <label for="name" class="form-label">Nome</label>
                            <input type="text" class="form-control" id="name" name="name" value="{{ old('name', $game->name) }}" required>
                        </div>
                        <div class="mb-3">
                            <label for="type" class="form-label">Tipo</label>
                            <input type="text" class="form-control" id="type" name="type" value="{{ old('type', $game->type) }}" required>
                        </div>
                        <div class="mb-3">
                            <label for="price_per_bet" class="form-label">Preço por Aposta (€)</label>
                            <input type="number" step="0.01" min="0" class="form-control" id="price_per_bet" name="price_per_bet" value="{{ old('price_per_bet', $game->price_per_bet) }}" required>
                        </div>
                        <div class="mb-3">
                            <label for="description" class="form-label">Descrição</label>
                            <textarea class="form-control" id="description" name="description" rows="3">{{ old('description', $game->description) }}</textarea>
                        </div>
                        <div class="mb-3">
                            <label for="rules" class="form-label">Regras</label>
                            <textarea class="form-control" id="rules" name="rules" rows="5">{{ old('rules', $game->rules) }}</textarea>
                        </div>
                        <div class="d-flex justify-content-end">
                            <button type="submit" class="btn btn-primary">Salvar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
