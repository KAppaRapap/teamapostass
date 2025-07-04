@extends('layouts.app')

@section('content')
<div class="py-16">
    <div class="max-w-4xl mx-auto">
        <!-- Header -->
        <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center mb-12">
            <div>
                <h1 class="font-orbitron font-bold text-4xl lg:text-5xl mb-4">
                    <span class="text-neon-green">Gerir</span> Utilizador
                </h1>
                <p class="text-xl text-gray-300">Funções administrativas para {{ $user->name }}</p>
            </div>
            <a href="{{ route('admin.users.index') }}" class="btn-outline mt-4 lg:mt-0">
                <i class="fas fa-arrow-left mr-2"></i>Voltar à Lista
            </a>
        </div>

        @if(session('success'))
        <div class="bg-green-600 text-white rounded-lg px-6 py-4 mb-8 flex items-center gap-3">
            <i class="fas fa-check-circle text-xl"></i>
            <span class="text-lg">{{ session('success') }}</span>
        </div>
        @endif

        @if($errors->any())
        <div class="bg-red-600 text-white rounded-lg px-6 py-4 mb-8">
            <h4 class="font-bold mb-2">Erros encontrados:</h4>
            <ul class="list-disc list-inside">
                @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <!-- Aviso Informativo -->
        <div class="bg-blue-900 border border-blue-600 text-blue-100 rounded-lg px-6 py-4 mb-8 flex items-start gap-3">
            <i class="fas fa-info-circle text-xl text-blue-400 mt-1"></i>
            <div>
                <h4 class="font-bold mb-2">Política de Privacidade de Dados</h4>
                <p class="text-sm">
                    Os dados pessoais (nome e email) só podem ser alterados pelo próprio utilizador através do seu perfil.
                    Como administrador, pode apenas gerir o saldo virtual, status da conta e permissões administrativas.
                </p>
            </div>
        </div>

        <!-- Informações do Utilizador -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-8">
            <!-- Avatar e Info Básica -->
            <div class="content-card p-8 text-center">
                <img src="{{ $user->profile_photo_url }}" alt="{{ $user->name }}"
                     class="w-32 h-32 rounded-full border-4 border-neon-green mx-auto mb-4">
                <h3 class="text-2xl font-bold text-white mb-2">{{ $user->name }}</h3>
                <p class="text-gray-400 mb-4">{{ $user->email }}</p>
                <div class="flex justify-center gap-2">
                    @if($user->is_admin)
                    <span class="px-3 py-1 bg-neon-green text-dark-bg text-xs font-bold rounded">ADMIN</span>
                    @endif
                    @if($user->is_banned)
                    <span class="px-3 py-1 bg-red-600 text-white text-xs font-bold rounded">BANIDO</span>
                    @else
                    <span class="px-3 py-1 bg-green-600 text-white text-xs font-bold rounded">ATIVO</span>
                    @endif
                </div>
            </div>

            <!-- Estatísticas -->
            <div class="content-card p-8">
                <h4 class="text-xl font-bold text-white mb-6">Estatísticas</h4>
                <div class="space-y-4">
                    <div class="flex justify-between">
                        <span class="text-gray-400">Saldo Atual:</span>
                        <span class="text-neon-green font-bold">€{{ number_format($user->virtual_balance, 2) }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-400">Registrado em:</span>
                        <span class="text-white">{{ $user->created_at->format('d/m/Y') }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-400">Último acesso:</span>
                        <span class="text-white">{{ $user->updated_at->diffForHumans() }}</span>
                    </div>
                </div>
            </div>

            <!-- Ações Rápidas -->
            <div class="content-card p-8">
                <h4 class="text-xl font-bold text-white mb-6">Ações Rápidas</h4>
                <div class="space-y-3">
                    <!-- Toggle Ban -->
                    <form method="POST" action="{{ route('admin.users.toggle-ban', $user) }}">
                        @csrf
                        <button type="submit"
                                class="btn-outline w-full {{ $user->is_banned ? 'text-green-400 border-green-400' : 'text-red-400 border-red-400' }}">
                            <i class="fas {{ $user->is_banned ? 'fa-user-check' : 'fa-user-slash' }} mr-2"></i>
                            {{ $user->is_banned ? 'Desbanir Utilizador' : 'Banir Utilizador' }}
                        </button>
                    </form>

                    <!-- Toggle Admin -->
                    @if($user->id !== auth()->id())
                    <form method="POST" action="{{ route('admin.users.toggle-admin', $user) }}">
                        @csrf
                        <button type="submit"
                                class="btn-outline w-full {{ $user->is_admin ? 'text-orange-400 border-orange-400' : 'text-blue-400 border-blue-400' }}">
                            <i class="fas {{ $user->is_admin ? 'fa-user-minus' : 'fa-user-plus' }} mr-2"></i>
                            {{ $user->is_admin ? 'Remover Admin' : 'Tornar Admin' }}
                        </button>
                    </form>
                    @endif
                </div>
            </div>
        </div>

        <!-- Formulários de Edição -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <!-- Dados Pessoais (Apenas Visualização) -->
            <div class="content-card p-8">
                <h3 class="text-2xl font-bold text-white mb-6">
                    <i class="fas fa-user text-neon-green mr-3"></i>Dados Pessoais
                </h3>
                <div class="space-y-4">
                    <div class="bg-gray-800 p-4 rounded-lg border-l-4 border-gray-600">
                        <label class="block text-gray-400 text-sm mb-1">Nome</label>
                        <p class="text-white font-semibold">{{ $user->name }}</p>
                        <small class="text-gray-500 italic">Este campo só pode ser alterado pelo próprio utilizador</small>
                    </div>

                    <div class="bg-gray-800 p-4 rounded-lg border-l-4 border-gray-600">
                        <label class="block text-gray-400 text-sm mb-1">Email</label>
                        <p class="text-white font-semibold">{{ $user->email }}</p>
                        <small class="text-gray-500 italic">Este campo só pode ser alterado pelo próprio utilizador</small>
                    </div>

                    <div class="bg-gray-800 p-4 rounded-lg border-l-4 border-neon-green">
                        <label class="block text-gray-400 text-sm mb-1">Saldo Virtual</label>
                        <p class="text-neon-green font-bold text-xl">€{{ number_format($user->virtual_balance, 2) }}</p>
                        <small class="text-gray-500 italic">Use o formulário "Ajustar Saldo" para modificar</small>
                    </div>

                    <div class="bg-gray-800 p-4 rounded-lg border-l-4 border-blue-500">
                        <label class="block text-gray-400 text-sm mb-1">Membro desde</label>
                        <p class="text-white font-semibold">{{ $user->created_at->format('d/m/Y H:i') }}</p>
                    </div>

                    <div class="bg-gray-800 p-4 rounded-lg border-l-4 border-purple-500">
                        <label class="block text-gray-400 text-sm mb-1">Última atividade</label>
                        <p class="text-white font-semibold">{{ $user->updated_at->diffForHumans() }}</p>
                    </div>

                    <!-- Status atual -->
                    <div class="bg-gray-800 p-4 rounded-lg border-l-4 border-yellow-500">
                        <label class="block text-gray-400 text-sm mb-1">Status da Conta</label>
                        <div class="flex gap-2 mt-2">
                            @if($user->is_admin)
                            <span class="px-3 py-1 bg-neon-green text-dark-bg text-xs font-bold rounded">ADMINISTRADOR</span>
                            @endif
                            @if($user->is_banned)
                            <span class="px-3 py-1 bg-red-600 text-white text-xs font-bold rounded">BANIDO</span>
                            @else
                            <span class="px-3 py-1 bg-green-600 text-white text-xs font-bold rounded">ATIVO</span>
                            @endif
                        </div>
                        <small class="text-gray-500 italic">Use as "Ações Rápidas" para modificar o status</small>
                    </div>
                </div>
            </div>

            <!-- Ajustar Saldo -->
            <div class="content-card p-8 bg-black border-2 border-neon-green">
                <h3 class="text-2xl font-bold text-white mb-6">
                    <i class="fas fa-coins text-yellow-400 mr-3"></i>Ajustar Saldo
                </h3>
                <form method="POST" action="{{ route('admin.users.adjust-balance', $user) }}">
                    @csrf

                    <div class="space-y-6">
                        <div>
                            <label class="block text-neon-green font-semibold mb-2">Tipo de Ajuste</label>
                            <select name="type" class="form-input w-full bg-black border-neon-green text-white" required style="background-color: black !important; color: white !important;">
                                <option value="add" style="background-color: black; color: white;">Adicionar ao saldo</option>
                                <option value="subtract" style="background-color: black; color: white;">Subtrair do saldo</option>
                                <option value="set" style="background-color: black; color: white;">Definir saldo exato</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-neon-green font-semibold mb-2">Valor (€)</label>
                            <input type="number" name="amount" step="0.01" min="0"
                                   class="form-input w-full bg-gray-900 border-gray-700 text-white" required>
                        </div>

                        <div>
                            <label class="block text-neon-green font-semibold mb-2">Motivo</label>
                            <textarea name="reason" rows="3" class="form-input w-full bg-gray-900 border-gray-700 text-white"
                                      placeholder="Descreva o motivo do ajuste..." required></textarea>
                        </div>

                        <button type="submit" class="btn-primary w-full">
                            <i class="fas fa-calculator mr-2"></i>Ajustar Saldo
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
