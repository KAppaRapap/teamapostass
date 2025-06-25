@extends('layouts.app')

@section('title', 'Registo')
@section('description', 'Crie a sua conta para começar a apostar em grupo')

@section('content')
<div class="flex justify-center items-center min-h-[70vh] py-12">
    <div class="content-card max-w-md w-full mx-auto animate-fade-in">
        <div class="text-center mb-6">
            <a href="{{ url('/') }}" class="inline-flex items-center gap-2 mb-2">
                <img src="{{ asset('img/logo1111.png') }}" alt="TeamApostas" class="h-10 w-auto mx-auto">
            </a>
            <h2 class="font-orbitron font-bold text-2xl text-neon-green mb-1">TeamApostas</h2>
            <p class="text-gray-400">Crie a sua conta para começar a apostar em grupo</p>
        </div>
        
        @if ($errors->any())
            <div class="flash-message bg-red-600/20 text-red-400 px-4 py-2 rounded mb-4">
                <ul class="mb-0 list-disc pl-5">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        
        <form method="POST" action="{{ route('register') }}" class="space-y-5">
            @csrf
            <div>
                <label for="name" class="block text-sm font-medium text-gray-300 mb-1">Nome</label>
                <input id="name" name="name" type="text" value="{{ old('name') }}" required autofocus class="form-input w-full" placeholder="O seu nome">
            </div>
            <div>
                <label for="email" class="block text-sm font-medium text-gray-300 mb-1">E-mail</label>
                <input id="email" name="email" type="email" value="{{ old('email') }}" required class="form-input w-full" placeholder="O seu e-mail">
            </div>
            <div>
                <label for="city" class="block text-sm font-medium text-gray-300 mb-1">Cidade</label>
                <input id="city" name="city" type="text" value="{{ old('city') }}" class="form-input w-full" placeholder="A sua cidade">
            </div>
            <div>
                <label for="password" class="block text-sm font-medium text-gray-300 mb-1">Palavra-passe</label>
                <input id="password" name="password" type="password" required autocomplete="new-password" class="form-input w-full" placeholder="Crie uma palavra-passe">
            </div>
            <div>
                <label for="password_confirmation" class="block text-sm font-medium text-gray-300 mb-1">Confirmar Palavra-passe</label>
                <input id="password_confirmation" name="password_confirmation" type="password" required class="form-input w-full" placeholder="Confirme a palavra-passe">
            </div>
            <div class="flex items-center gap-2">
                <input class="rounded border-gray-600 bg-dark-bg" type="checkbox" id="accept_terms" name="accept_terms" required>
                <label class="text-sm text-gray-400" for="accept_terms">
                    Aceito os <a href="{{ route('legal.terms') }}" target="_blank" class="text-neon-green hover:underline">Termos de Utilização</a> e a <a href="{{ route('legal.privacy') }}" target="_blank" class="text-neon-green hover:underline">Política de Privacidade</a>
                </label>
            </div>
            <button type="submit" class="btn-primary w-full text-lg">Registar</button>
        </form>
        <div class="text-center mt-6">
            <p class="text-gray-400">Já tem uma conta? <a href="{{ route('login') }}" class="text-neon-green hover:underline">Entrar</a></p>
        </div>
    </div>
</div>
@endsection
