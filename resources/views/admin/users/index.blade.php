@extends('layouts.app')

@section('content')
<div class="py-16">
    <div class="max-w-8xl mx-auto">
        <!-- Header -->
        <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center mb-12">
            <div>
                <h1 class="font-orbitron font-bold text-4xl lg:text-5xl mb-4">
                    <span class="text-neon-green">Gerir</span> Utilizadores
                </h1>
                <p class="text-xl text-gray-300">Controlo total sobre os utilizadores da plataforma</p>
            </div>
            <a href="{{ route('admin.dashboard') }}" class="btn-outline mt-4 lg:mt-0">
                <i class="fas fa-arrow-left mr-2"></i>Voltar ao Dashboard
            </a>
        </div>

        @if(session('success'))
        <div class="bg-green-600 text-white rounded-lg px-6 py-4 mb-8 flex items-center gap-3">
            <i class="fas fa-check-circle text-xl"></i>
            <span class="text-lg">{{ session('success') }}</span>
        </div>
        @endif

        <!-- Filtros e Pesquisa -->
        <div class="content-card p-8 mb-8">
            <form method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-6">
                <div>
                    <label class="block text-white font-semibold mb-2">Pesquisar</label>
                    <input type="text" name="search" value="{{ request('search') }}"
                           placeholder="Nome ou email..."
                           class="form-input w-full">
                </div>
                <div>
                    <label class="block text-white font-semibold mb-2">Status</label>
                    <select name="status" class="form-input w-full">
                        <option value="">Todos</option>
                        <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Ativos</option>
                        <option value="banned" {{ request('status') == 'banned' ? 'selected' : '' }}>Banidos</option>
                        <option value="admin" {{ request('status') == 'admin' ? 'selected' : '' }}>Admins</option>
                    </select>
                </div>
                <div class="flex items-end">
                    <button type="submit" class="btn-primary w-full">
                        <i class="fas fa-search mr-2"></i>Filtrar
                    </button>
                </div>
                <div class="flex items-end">
                    <a href="{{ route('admin.users.index') }}" class="btn-outline w-full text-center">
                        <i class="fas fa-times mr-2"></i>Limpar
                    </a>
                </div>
            </form>
        </div>
        <!-- Tabela de Utilizadores -->
        <div class="content-card overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full">
                    <thead class="bg-gray-800">
                        <tr>
                            <th class="px-6 py-4 text-left text-neon-green font-semibold">Avatar</th>
                            <th class="px-6 py-4 text-left text-neon-green font-semibold">Utilizador</th>
                            <th class="px-6 py-4 text-center text-neon-green font-semibold">Saldo</th>
                            <th class="px-6 py-4 text-center text-neon-green font-semibold">Status</th>
                            <th class="px-6 py-4 text-center text-neon-green font-semibold">Registrado</th>
                            <th class="px-6 py-4 text-center text-neon-green font-semibold">Ações</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-700">
                        @foreach($users as $user)
                        <tr class="hover:bg-gray-800 transition-colors">
                            <!-- Avatar -->
                            <td class="px-6 py-4">
                                <img src="{{ $user->profile_photo_url }}" alt="{{ $user->name }}"
                                     class="w-12 h-12 rounded-full border-2 border-neon-green">
                            </td>

                            <!-- Utilizador -->
                            <td class="px-6 py-4">
                                <div>
                                    <p class="font-semibold text-white text-lg">{{ $user->name }}</p>
                                    <p class="text-gray-400">{{ $user->email }}</p>
                                </div>
                            </td>

                            <!-- Saldo -->
                            <td class="px-6 py-4 text-center">
                                <span class="text-xl font-bold text-neon-green">€{{ number_format($user->virtual_balance, 2) }}</span>
                            </td>

                            <!-- Status -->
                            <td class="px-6 py-4 text-center">
                                <div class="flex flex-col gap-2">
                                    @if($user->is_admin)
                                    <span class="px-3 py-1 rounded-full bg-neon-green text-dark-bg text-xs font-bold">ADMIN</span>
                                    @endif
                                    @if($user->is_banned)
                                    <span class="px-3 py-1 rounded-full bg-red-600 text-white text-xs font-bold">BANIDO</span>
                                    @else
                                    <span class="px-3 py-1 rounded-full bg-green-600 text-white text-xs font-bold">ATIVO</span>
                                    @endif
                                </div>
                            </td>

                            <!-- Data de Registro -->
                            <td class="px-6 py-4 text-center text-gray-400">
                                {{ $user->created_at->format('d/m/Y') }}
                            </td>

                            <!-- Ações -->
                            <td class="px-6 py-4">
                                <div class="flex flex-col gap-2">
                                    <!-- Editar -->
                                    <a href="{{ route('admin.users.edit', $user) }}"
                                       class="btn-outline text-xs py-1 px-3 text-center">
                                        <i class="fas fa-edit mr-1"></i>Editar
                                    </a>

                                    <!-- Toggle Ban -->
                                    <form method="POST" action="{{ route('admin.users.toggle-ban', $user) }}" class="inline">
                                        @csrf
                                        <button type="submit"
                                                class="btn-outline text-xs py-1 px-3 w-full {{ $user->is_banned ? 'text-green-400 border-green-400' : 'text-red-400 border-red-400' }}">
                                            <i class="fas {{ $user->is_banned ? 'fa-user-check' : 'fa-user-slash' }} mr-1"></i>
                                            {{ $user->is_banned ? 'Desbanir' : 'Banir' }}
                                        </button>
                                    </form>

                                    <!-- Toggle Admin -->
                                    @if($user->id !== auth()->id())
                                    <form method="POST" action="{{ route('admin.users.toggle-admin', $user) }}" class="inline">
                                        @csrf
                                        <button type="submit"
                                                class="btn-outline text-xs py-1 px-3 w-full {{ $user->is_admin ? 'text-orange-400 border-orange-400' : 'text-blue-400 border-blue-400' }}">
                                            <i class="fas {{ $user->is_admin ? 'fa-user-minus' : 'fa-user-plus' }} mr-1"></i>
                                            {{ $user->is_admin ? 'Remover Admin' : 'Tornar Admin' }}
                                        </button>
                                    </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Paginação -->
        @if($users->hasPages())
        <div class="mt-8">
            {{ $users->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
