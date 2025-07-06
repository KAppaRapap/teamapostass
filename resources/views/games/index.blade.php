@extends('layouts.app')

@section('title', 'Jogos')
@section('description', 'Explora todos os jogos disponíveis no TeamApostas')

@section('content')
<div class="py-8 px-6">
    <div class="max-w-7xl mx-auto">
        <!-- Header -->
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8">
            <div>
                <h1 class="font-orbitron font-bold text-3xl lg:text-4xl mb-2">
                    <span class="text-neon-green">Jogos</span> Disponíveis
                </h1>
                <p class="text-gray-300">Escolhe o teu jogo favorito e começa a apostar</p>
            </div>
        </div>

        <!-- Flash Messages -->
        @if(session('success'))
        <div class="bg-green-500 bg-opacity-20 border border-green-500 text-green-300 px-4 py-3 rounded-lg mb-6 flash-message">
            <i class="fas fa-check-circle mr-2"></i>{{ session('success') }}
        </div>
        @endif

        <!-- Jogos Disponíveis -->
        <div>
            <div class="grid md:grid-cols-3 gap-8">
                <!-- Dice -->
                <div class="content-card text-center group">
                    <div class="text-5xl mb-4 group-hover:scale-110 transition-transform duration-300">🎲</div>
                    <h3 class="font-orbitron font-bold text-xl mb-3 text-white">Dice</h3>
                    <p class="text-gray-300 mb-6">Aposta no número e desafia a sorte. Ganhos instantâneos para os mais ousados!</p>
                    <a href="{{ route('games.dice') }}" class="btn-primary w-full">
                        <i class="fas fa-play mr-2"></i> Jogar Agora
                    </a>
                </div>
                
                <!-- Bomb Mine -->
                <div class="content-card text-center group">
                    <div class="text-5xl mb-4 group-hover:scale-110 transition-transform duration-300">💣</div>
                    <h3 class="font-orbitron font-bold text-xl mb-3 text-white">Bomb Mine</h3>
                    <p class="text-gray-300 mb-6">Evita as bombas e recolhe moedas. Cada jogada é adrenalina pura!</p>
                    <a href="{{ route('games.bombmine') }}" class="btn-primary w-full">
                        <i class="fas fa-play mr-2"></i> Jogar Agora
                    </a>
                </div>
                
                <!-- Crash -->
                <div class="content-card text-center group">
                    <div class="text-5xl mb-4 group-hover:scale-110 transition-transform duration-300">📉</div>
                    <h3 class="font-orbitron font-bold text-xl mb-3 text-white">Crash</h3>
                    <p class="text-gray-300 mb-6">Aposta antes do gráfico colapsar. Retira a tempo e multiplica os teus ganhos!</p>
                    <a href="{{ route('games.crash') }}" class="btn-primary w-full">
                        <i class="fas fa-play mr-2"></i> Jogar Agora
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
