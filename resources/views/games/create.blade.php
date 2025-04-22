@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0">Criar Novo Jogo</h2>
        <a href="{{ route('games.index') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-1"></i> Voltar
        </a>
    </div>

    @if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
    @endif

    <div class="card">
        <div class="card-body">
            <form action="{{ route('games.store') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label for="name" class="form-label">Nome do Jogo</label>
                    <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}" required>
                    @error('name')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="type" class="form-label">Tipo de Jogo</label>
                    <select class="form-select @error('type') is-invalid @enderror" id="type" name="type" required>
                        <option value="" selected disabled>Selecione o tipo de jogo</option>
                        <option value="Euromilhões" {{ old('type') == 'Euromilhões' ? 'selected' : '' }}>Euromilhões</option>
                        <option value="Totoloto" {{ old('type') == 'Totoloto' ? 'selected' : '' }}>Totoloto</option>
                        <option value="Totobola" {{ old('type') == 'Totobola' ? 'selected' : '' }}>Totobola</option>
                        <option value="Placard" {{ old('type') == 'Placard' ? 'selected' : '' }}>Placard</option>
                    </select>
                    @error('type')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="price_per_bet" class="form-label">Preço por Aposta (€)</label>
                    <input type="number" step="0.01" min="0" class="form-control @error('price_per_bet') is-invalid @enderror" id="price_per_bet" name="price_per_bet" value="{{ old('price_per_bet') }}" required>
                    @error('price_per_bet')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="description" class="form-label">Descrição</label>
                    <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="3">{{ old('description') }}</textarea>
                    @error('description')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="rules" class="form-label">Regras</label>
                    <textarea class="form-control @error('rules') is-invalid @enderror" id="rules" name="rules" rows="5">{{ old('rules') }}</textarea>
                    @error('rules')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-1"></i> Salvar Jogo
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
