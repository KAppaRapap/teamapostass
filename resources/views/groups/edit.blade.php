@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0">Editar Grupo</h2>
        <a href="{{ route('groups.chat', $group) }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-1"></i> Voltar
        </a>
    </div>

    <div class="card">
        <div class="card-body">
            <form action="{{ route('groups.update', $group) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label for="name" class="form-label">Nome do Grupo *</label>
                    <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $group->name) }}" required>
                    @error('name')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="description" class="form-label">Descrição</label>
                    <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="3">{{ old('description', $group->description) }}</textarea>
                    @error('description')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="city" class="form-label">Cidade</label>
                        <input type="text" class="form-control @error('city') is-invalid @enderror" id="city" name="city" value="{{ old('city', $group->city) }}">
                        @error('city')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6">
                        <label for="region" class="form-label">Região</label>
                        <input type="text" class="form-control @error('region') is-invalid @enderror" id="region" name="region" value="{{ old('region', $group->region) }}">
                        @error('region')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="mb-3">
                    <label for="max_members" class="form-label">Número Máximo de Membros</label>
                    <input type="number" class="form-control @error('max_members') is-invalid @enderror" id="max_members" name="max_members" value="{{ old('max_members', $group->max_members) }}" min="0">
                    <div class="form-text">Deixe em branco ou 0 para ilimitado.</div>
                    @error('max_members')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-4">
                    <div class="form-check">
                        <input class="form-check-input @error('is_public') is-invalid @enderror" type="checkbox" id="is_public" name="is_public" value="1" {{ old('is_public', $group->is_public) ? 'checked' : '' }}>
                        <label class="form-check-label" for="is_public">
                            Grupo Público
                        </label>
                        <div class="form-text">Grupos públicos são visíveis para todos os utilizadores.</div>
                        @error('is_public')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="d-grid">
                    <button type="submit" class="btn btn-primary btn-lg">
                        <i class="fas fa-save me-1"></i> Atualizar Grupo
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
