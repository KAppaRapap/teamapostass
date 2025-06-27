@extends('layouts.app')

@section('content')
<div class="min-h-screen flex flex-col items-center justify-center bg-black py-8 px-4">
    <div class="w-full max-w-lg bg-neutral-900 rounded-2xl shadow-lg p-8 border border-neutral-800">
        <div class="flex items-center mb-6 gap-2">
            <a href="{{ route('groups.index') }}" class="flex items-center gap-1 px-4 py-2 rounded-full bg-transparent text-neon-green font-semibold transition hover:bg-neon-green/10 hover:scale-105 focus:outline-none focus:ring-2 focus:ring-neon-green">
                <i class="fas fa-arrow-left"></i> <span>Voltar</span>
            </a>
            <h2 class="text-2xl font-bold text-white ml-auto">Criar Novo Grupo</h2>
        </div>
        <form action="{{ route('groups.store') }}" method="POST" class="space-y-5">
            @csrf
            <div>
                <label for="name" class="block text-sm font-semibold text-black mb-1">Nome do Grupo *</label>
                <input type="text" id="name" name="name" value="{{ old('name') }}" required
                    class="w-full rounded-lg border border-neutral-300 bg-white !text-black placeholder-gray-500 px-4 py-2 focus:outline-none focus:ring-2 focus:ring-neon-green transition @error('name') border-red-500 @enderror" placeholder="Digite o nome do grupo" style="color:#000">
                @error('name')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>
            <div>
                <label for="description" class="block text-sm font-semibold text-black mb-1">Descrição</label>
                <textarea id="description" name="description" rows="3"
                    class="w-full rounded-lg border border-neutral-300 bg-white !text-black placeholder-gray-500 px-4 py-2 focus:outline-none focus:ring-2 focus:ring-neon-green transition @error('description') border-red-500 @enderror" placeholder="Descreva o grupo (opcional)" style="color:#000">{{ old('description') }}</textarea>
                @error('description')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>
            <div class="flex gap-4">
                <div class="w-1/2">
                    <label for="city" class="block text-sm font-semibold text-black mb-1">Cidade</label>
                    <input type="text" id="city" name="city" value="{{ old('city') }}"
                        class="w-full rounded-lg border border-neutral-300 bg-white !text-black placeholder-gray-500 px-4 py-2 focus:outline-none focus:ring-2 focus:ring-neon-green transition @error('city') border-red-500 @enderror" placeholder="Cidade" style="color:#000">
                    @error('city')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div class="w-1/2">
                    <label for="region" class="block text-sm font-semibold text-black mb-1">Região</label>
                    <input type="text" id="region" name="region" value="{{ old('region') }}"
                        class="w-full rounded-lg border border-neutral-300 bg-white !text-black placeholder-gray-500 px-4 py-2 focus:outline-none focus:ring-2 focus:ring-neon-green transition @error('region') border-red-500 @enderror" placeholder="Região" style="color:#000">
                    @error('region')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>
            <div>
                <label for="max_members" class="block text-sm font-semibold text-black mb-1">Número Máximo de Membros</label>
                <input type="number" id="max_members" name="max_members" value="{{ old('max_members') }}" min="0"
                    class="w-full rounded-lg border border-neutral-300 bg-white !text-black placeholder-gray-500 px-4 py-2 focus:outline-none focus:ring-2 focus:ring-neon-green transition @error('max_members') border-red-500 @enderror" placeholder="0 para ilimitado" style="color:#000">
                <span class="text-xs text-gray-700">Deixe em branco ou 0 para ilimitado.</span>
                @error('max_members')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>
            <div>
                <label class="inline-flex items-center">
                    <input type="checkbox" id="is_public" name="is_public" value="1" class="rounded border-neutral-300 bg-white text-neon-green focus:ring-neon-green" {{ old('is_public') ? 'checked' : '' }} style="accent-color:#00FFB2">
                    <span class="ml-2 text-black font-semibold">Grupo Público</span>
                </label>
                <span class="block text-xs text-gray-700 mt-1">Grupos públicos são visíveis para todos os usuários.</span>
                @error('is_public')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>
            <button type="submit" class="w-full bg-neon-green hover:bg-green-400 text-black font-bold py-4 rounded-full text-lg transition-all duration-200 flex items-center justify-center gap-2 shadow-xl transform hover:scale-105 active:scale-100 focus:outline-none focus:ring-2 focus:ring-neon-green">
                <i class="fas fa-save"></i> Criar Grupo
            </button>
        </form>
    </div>
</div>
@endsection
