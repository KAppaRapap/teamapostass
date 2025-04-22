@extends('layouts.app')

@section('content')
<div class="container py-4">
    <h2>Editar Usuário</h2>

    @if($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('admin.users.update', $user) }}">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label for="name" class="form-label">Nome</label>
            <input type="text" name="name" id="name" class="form-control" value="{{ old('name', $user->name) }}" required>
        </div>

        <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input type="email" name="email" id="email" class="form-control" value="{{ old('email', $user->email) }}" required>
        </div>

        <div class="form-check mb-3">
            <input type="checkbox" name="is_admin" id="is_admin" class="form-check-input" {{ $user->is_admin ? 'checked' : '' }}>
            <label for="is_admin" class="form-check-label">Administrador</label>
        </div>

        <div class="form-check mb-3">
            <input type="checkbox" name="is_banned" id="is_banned" class="form-check-input" {{ $user->is_banned ? 'checked' : '' }}>
            <label for="is_banned" class="form-check-label">Banido</label>
        </div>

        <button type="submit" class="btn btn-primary">Salvar Alterações</button>
        <a href="{{ route('admin.users.index') }}" class="btn btn-secondary ms-2">Cancelar</a>
    </form>
</div>
@endsection
