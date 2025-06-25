@extends('layouts.app')

@section('content')
<div class="max-w-6xl mx-auto py-10 px-4">
    <h2 class="font-orbitron text-2xl font-bold text-white mb-8">Gerenciar Usuários</h2>
    @if(session('success'))
    <div class="bg-green-600 text-white rounded-lg px-4 py-2 mb-4 animate__animated animate__fadeInDown">
        {{ session('success') }}
    </div>
    @endif
    <div class="overflow-x-auto rounded-lg shadow-lg">
        <table class="min-w-full table-auto bg-dark-card border border-dark-border text-white">
            <thead>
                <tr class="bg-dark-bg text-neon-green uppercase text-sm">
                    <th class="px-6 py-4 text-left">Nome</th>
                    <th class="px-6 py-4 text-left">Email</th>
                    <th class="px-6 py-4 text-center">Admin</th>
                    <th class="px-6 py-4 text-center">Banido</th>
                    <th class="px-6 py-4 text-center">Ações</th>
                </tr>
            </thead>
            <tbody>
                @foreach($users as $user)
                <tr class="border-b border-dark-border hover:bg-dark-bg/70 transition-colors">
                    <td class="px-6 py-4 font-semibold">{{ $user->name }}</td>
                    <td class="px-6 py-4">{{ $user->email }}</td>
                    <td class="px-6 py-4 text-center">
                        @if($user->is_admin)
                        <span class="inline-block px-3 py-1 rounded-full bg-gradient-to-br from-neon-green to-green-600 text-dark-bg text-xs font-bold shadow">Sim</span>
                        @else
                        <span class="inline-block px-3 py-1 rounded-full bg-gray-700 text-gray-300 text-xs font-bold">Não</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 text-center">
                        @if($user->is_banned)
                        <span class="inline-block px-3 py-1 rounded-full bg-gradient-to-br from-neon-pink to-pink-600 text-white text-xs font-bold shadow">Sim</span>
                        @else
                        <span class="inline-block px-3 py-1 rounded-full bg-gray-700 text-gray-300 text-xs font-bold">Não</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 text-center">
                        <a href="{{ route('admin.users.edit', $user) }}" class="btn-primary px-6 py-2 text-base rounded-full shadow hover:scale-105 transition-transform">Editar</a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
