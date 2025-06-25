@extends('layouts.app')

@section('content')
<div class="container mx-auto py-8">
    <h1 class="text-2xl font-bold mb-4 text-white">Perfil</h1>
    @if(session('success'))
    <div class="bg-green-600 text-white rounded p-3 mb-4">{{ session('success') }}</div>
    @endif
    <div class="flex flex-col md:flex-row gap-8">
        <!-- Abas laterais -->
        <div class="w-full md:w-1/4">
            <div class="bg-gray-900 rounded-lg shadow p-4 border border-gray-800 flex flex-col gap-2">
                <button class="tab-btn text-left px-4 py-2 rounded text-white font-semibold hover:bg-neon-green hover:text-dark-bg transition-colors active" data-tab="#tab-profile">
                    <i class="fas fa-user mr-2"></i> Perfil
                </button>
                <button class="tab-btn text-left px-4 py-2 rounded text-white font-semibold hover:bg-neon-green hover:text-dark-bg transition-colors" data-tab="#tab-password">
                    <i class="fas fa-lock mr-2"></i> Senha
                </button>
                <button class="tab-btn text-left px-4 py-2 rounded text-white font-semibold hover:bg-neon-green hover:text-dark-bg transition-colors" data-tab="#tab-notifications">
                    <i class="fas fa-bell mr-2"></i> Notificações
                </button>
            </div>
        </div>
        <!-- Conteúdo das abas -->
        <div class="w-full md:w-3/4">
            <div id="tab-profile" class="tab-content bg-gray-900 rounded-lg shadow p-6 border border-gray-800">
                <h5 class="text-xl font-bold text-white mb-6">Informações do Perfil</h5>
                <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                    @csrf
                    @method('PUT')
                    <div>
                        <label for="name" class="block text-gray-200 font-bold mb-2">Nome</label>
                        <input type="text" id="name" name="name" value="{{ old('name', $user->name) }}" required class="w-full border border-gray-700 bg-gray-800 text-white rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-neon-green">
                    </div>
                    <div>
                        <label for="email" class="block text-gray-200 font-bold mb-2">Email</label>
                        <input type="email" id="email" name="email" value="{{ old('email', $user->email) }}" required class="w-full border border-gray-700 bg-gray-800 text-white rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-neon-green">
                    </div>
                    <div>
                        <label for="city" class="block text-gray-200 font-bold mb-2">Cidade</label>
                        <input type="text" id="city" name="city" value="{{ old('city', $user->city) }}" class="w-full border border-gray-700 bg-gray-800 text-white rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-neon-green">
                    </div>
                    <div class="flex flex-col items-center gap-2">
                        <img src="{{ $user->profile_photo ? asset('storage/' . $user->profile_photo) : asset('img/default-avatar.png') }}" class="rounded-full" width="100" height="100" alt="Foto de Perfil">
                        <input type="file" name="profile_photo" accept="image/*" class="border border-gray-700 bg-gray-800 text-white rounded px-3 py-2 w-full">
                    </div>
                    <div>
                        <button type="submit" class="w-full bg-neon-green hover:bg-green-600 text-dark-bg font-bold py-2 px-4 rounded transition flex items-center justify-center gap-2">
                            <i class="fas fa-save"></i> Atualizar Perfil
                        </button>
                    </div>
                </form>
            </div>
            <div id="tab-password" class="tab-content bg-gray-900 rounded-lg shadow p-6 border border-gray-800 hidden">
                <h5 class="text-xl font-bold text-white mb-6">Alterar Senha</h5>
                <form action="{{ route('profile.update-password') }}" method="POST" class="space-y-4">
                    @csrf
                    @method('PUT')
                    <div>
                        <label for="current_password" class="block text-gray-200 font-bold mb-2">Senha Atual</label>
                        <input type="password" id="current_password" name="current_password" required class="w-full border border-gray-700 bg-gray-800 text-white rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-neon-green">
                    </div>
                    <div>
                        <label for="password" class="block text-gray-200 font-bold mb-2">Nova Senha</label>
                        <input type="password" id="password" name="password" required class="w-full border border-gray-700 bg-gray-800 text-white rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-neon-green">
                    </div>
                    <div>
                        <label for="password_confirmation" class="block text-gray-200 font-bold mb-2">Confirmar Nova Senha</label>
                        <input type="password" id="password_confirmation" name="password_confirmation" required class="w-full border border-gray-700 bg-gray-800 text-white rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-neon-green">
                    </div>
                    <div>
                        <button type="submit" class="w-full bg-neon-green hover:bg-green-600 text-dark-bg font-bold py-2 px-4 rounded transition flex items-center justify-center gap-2">
                            <i class="fas fa-lock"></i> Atualizar Senha
                        </button>
                    </div>
                </form>
            </div>
            <div id="tab-notifications" class="tab-content bg-gray-900 rounded-lg shadow p-6 border border-gray-800 hidden">
                <h5 class="text-xl font-bold text-white mb-6">Preferências de Notificação</h5>
                <form action="{{ route('profile.update-notifications') }}" method="POST" class="space-y-4">
                    @csrf
                    @method('PUT')
                    <div class="flex items-center gap-3">
                        <input class="form-checkbox h-5 w-5 text-neon-green bg-gray-800 border-gray-700 rounded focus:ring-neon-green" type="checkbox" id="notify_winnings" name="notify_winnings" value="1" {{ $user->notify_winnings ? 'checked' : '' }}>
                        <label for="notify_winnings" class="text-gray-200 font-semibold">Ganhos em Jogos</label>
                    </div>
                    <p class="text-gray-400 text-sm mb-2 ml-8">Receber notificações quando você ganhar em algum jogo.</p>
                    <div class="flex items-center gap-3">
                        <input class="form-checkbox h-5 w-5 text-neon-green bg-gray-800 border-gray-700 rounded focus:ring-neon-green" type="checkbox" id="notify_new_games" name="notify_new_games" value="1" {{ $user->notify_new_games ? 'checked' : '' }}>
                        <label for="notify_new_games" class="text-gray-200 font-semibold">Novos Jogos Disponíveis</label>
                    </div>
                    <p class="text-gray-400 text-sm mb-2 ml-8">Receber notificações sobre novos jogos lançados no site.</p>
                    <div class="flex items-center gap-3">
                        <input class="form-checkbox h-5 w-5 text-neon-green bg-gray-800 border-gray-700 rounded focus:ring-neon-green" type="checkbox" id="notify_group_activities" name="notify_group_activities" value="1" {{ $user->notify_group_activities ? 'checked' : '' }}>
                        <label for="notify_group_activities" class="text-gray-200 font-semibold">Atividades de Grupo</label>
                    </div>
                    <p class="text-gray-400 text-sm mb-2 ml-8">Receber notificações sobre atividades nos grupos que você participa.</p>
                    <div>
                        <button type="submit" class="w-full bg-neon-green hover:bg-green-600 text-dark-bg font-bold py-2 px-4 rounded transition flex items-center justify-center gap-2">
                            <i class="fas fa-save"></i> Salvar Preferências
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<script>
    // Tabs JS
    document.querySelectorAll('.tab-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active', 'bg-neon-green', 'text-dark-bg'));
            this.classList.add('active', 'bg-neon-green', 'text-dark-bg');
            document.querySelectorAll('.tab-content').forEach(tab => tab.classList.add('hidden'));
            document.querySelector(this.getAttribute('data-tab')).classList.remove('hidden');
        });
    });
</script>
@endsection 