@extends('layouts.app')

@section('content')
<div class="min-h-screen py-16">
    <div class="max-w-4xl mx-auto">
        <!-- Header -->
        <div class="text-center mb-12">
            <a href="{{ route('groups.index') }}" class="inline-flex items-center gap-3 text-neon-green hover:text-white transition-colors duration-300 mb-8 group">
                <i class="fas fa-arrow-left text-xl group-hover:transform group-hover:-translate-x-1 transition-transform"></i>
                <span class="text-lg font-medium">Voltar aos Grupos</span>
            </a>
            <h1 class="font-orbitron font-bold text-5xl lg:text-6xl mb-6">
                Criar <span class="text-neon-green">Novo Grupo</span>
            </h1>
            <p class="text-xl text-gray-300 max-w-2xl mx-auto leading-relaxed">
                Cria o teu próprio grupo de apostadores e convida amigos para jogarem em equipa
            </p>
        </div>

        <!-- Form Card -->
        <div class="content-card max-w-3xl mx-auto p-12">
            <form action="{{ route('groups.store') }}" method="POST" class="space-y-8">
                @csrf

                <!-- Nome do Grupo -->
                <div class="space-y-3">
                    <label for="name" class="block text-lg font-semibold text-white mb-3">
                        <i class="fas fa-users text-neon-green mr-3"></i>Nome do Grupo *
                    </label>
                    <input type="text" id="name" name="name" value="{{ old('name') }}" required
                        class="form-input w-full text-lg py-4 px-6 @error('name') border-red-500 @enderror"
                        placeholder="Ex: Apostadores de Lisboa">
                    @error('name')
                    <p class="text-red-400 text-sm mt-2 flex items-center gap-2">
                        <i class="fas fa-exclamation-circle"></i>{{ $message }}
                    </p>
                    @enderror
                </div>

                <!-- Descrição -->
                <div class="space-y-3">
                    <label for="description" class="block text-lg font-semibold text-white mb-3">
                        <i class="fas fa-align-left text-neon-green mr-3"></i>Descrição do Grupo
                    </label>
                    <textarea id="description" name="description" rows="4"
                        class="form-input w-full text-lg py-4 px-6 @error('description') border-red-500 @enderror"
                        placeholder="Descreve o teu grupo, objetivos e regras...">{{ old('description') }}</textarea>
                    @error('description')
                    <p class="text-red-400 text-sm mt-2 flex items-center gap-2">
                        <i class="fas fa-exclamation-circle"></i>{{ $message }}
                    </p>
                    @enderror
                </div>
                <!-- Localização -->
                <div class="space-y-3">
                    <label class="block text-lg font-semibold text-white mb-3">
                        <i class="fas fa-map-marker-alt text-neon-green mr-3"></i>Localização
                    </label>
                    <div class="grid md:grid-cols-2 gap-6">
                        <div>
                            <input type="text" id="city" name="city" value="{{ old('city') }}"
                                class="form-input w-full text-lg py-4 px-6 @error('city') border-red-500 @enderror"
                                placeholder="Cidade">
                            @error('city')
                            <p class="text-red-400 text-sm mt-2 flex items-center gap-2">
                                <i class="fas fa-exclamation-circle"></i>{{ $message }}
                            </p>
                            @enderror
                        </div>
                        <div>
                            <input type="text" id="region" name="region" value="{{ old('region') }}"
                                class="form-input w-full text-lg py-4 px-6 @error('region') border-red-500 @enderror"
                                placeholder="Região">
                            @error('region')
                            <p class="text-red-400 text-sm mt-2 flex items-center gap-2">
                                <i class="fas fa-exclamation-circle"></i>{{ $message }}
                            </p>
                            @enderror
                        </div>
                    </div>
                </div>
                <!-- Configurações do Grupo -->
                <div class="grid md:grid-cols-2 gap-8">
                    <!-- Limite de Membros -->
                    <div class="space-y-3">
                        <label for="max_members" class="block text-lg font-semibold text-white mb-3">
                            <i class="fas fa-user-friends text-neon-green mr-3"></i>Limite de Membros
                        </label>
                        <input type="number" id="max_members" name="max_members" value="{{ old('max_members') }}" min="0"
                            class="form-input w-full text-lg py-4 px-6 @error('max_members') border-red-500 @enderror"
                            placeholder="0 para ilimitado">
                        <p class="text-gray-400 text-sm">Deixa em branco ou 0 para ilimitado</p>
                        @error('max_members')
                        <p class="text-red-400 text-sm mt-2 flex items-center gap-2">
                            <i class="fas fa-exclamation-circle"></i>{{ $message }}
                        </p>
                        @enderror
                    </div>

                    <!-- Visibilidade -->
                    <div class="space-y-3">
                        <label class="block text-lg font-semibold text-white mb-3">
                            <i class="fas fa-eye text-neon-green mr-3"></i>Visibilidade
                        </label>
                        <div class="flex items-center space-x-4 py-4">
                            <label class="inline-flex items-center cursor-pointer">
                                <input type="checkbox" id="is_public" name="is_public" value="1"
                                    class="w-6 h-6 text-neon-green bg-gray-800 border-gray-600 rounded focus:ring-neon-green focus:ring-2"
                                    {{ old('is_public') ? 'checked' : '' }}>
                                <span class="ml-3 text-white font-medium text-lg">Grupo Público</span>
                            </label>
                        </div>
                        <p class="text-gray-400 text-sm">Grupos públicos são visíveis para todos os utilizadores</p>
                        @error('is_public')
                        <p class="text-red-400 text-sm mt-2 flex items-center gap-2">
                            <i class="fas fa-exclamation-circle"></i>{{ $message }}
                        </p>
                        @enderror
                    </div>
                </div>
                <!-- Botão de Submissão -->
                <div class="pt-8 border-t border-gray-700">
                    <div class="flex flex-col sm:flex-row gap-4 justify-center">
                        <a href="{{ route('groups.index') }}"
                            class="btn-outline text-lg px-8 py-4 text-center">
                            <i class="fas fa-times mr-2"></i>Cancelar
                        </a>
                        <button type="submit"
                            class="btn-primary text-lg px-12 py-4 shadow-2xl">
                            <i class="fas fa-plus mr-2"></i>Criar Grupo
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
