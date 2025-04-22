@extends('layouts.app')

@section('content')
<div class="container py-4">
    <h2>Gerenciar Usuários</h2>
    @if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
    @endif
    <div class="table-responsive">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>Nome</th>
                    <th>Email</th>
                    <th>Admin</th>
                    <th>Banido</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                @foreach($users as $user)
                <tr>
                    <td>{{ $user->name }}</td>
                    <td>{{ $user->email }}</td>
                    <td>
                        @if($user->is_admin)
                        <span class="badge bg-success">Sim</span>
                        @else
                        <span class="badge bg-secondary">Não</span>
                        @endif
                    </td>
                    <td>
                        @if($user->is_banned)
                        <span class="badge bg-danger">Sim</span>
                        @else
                        <span class="badge bg-secondary">Não</span>
                        @endif
                    </td>
                    <td>
                        <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-sm btn-primary">Editar</a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
