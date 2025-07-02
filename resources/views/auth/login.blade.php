@extends('layouts.app')

@section('title', 'Iniciar Sessão')
@section('description', 'Faça login para aceder à sua conta')

@section('content')
<div class="flex justify-center items-center min-h-[70vh] py-12">
    <div class="content-card max-w-md w-full mx-auto animate-fade-in">
        <div class="text-center mb-6">
            <a href="{{ url('/') }}" class="inline-flex items-center gap-2 mb-2">
                <img src="{{ asset('img/logo1111.png') }}" alt="TeamApostas" class="h-10 w-auto mx-auto">
            </a>
            <h2 class="font-orbitron font-bold text-2xl text-neon-green mb-1">TeamApostas</h2>
            <p class="text-gray-400">Faça login para aceder à sua conta</p>
        </div>
        
        @if (session('status'))
            <div class="flash-message bg-green-600/20 text-green-400 px-4 py-2 rounded mb-4 text-center">
                {{ session('status') }}
            </div>
        @endif
        
        @if ($errors->any())
            <div class="flash-message bg-red-600/20 text-red-400 px-4 py-2 rounded mb-4">
                <ul class="mb-0 list-disc pl-5">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        
        <form method="POST" action="{{ route('login') }}" class="space-y-5">
            @csrf
            <div>
                <label for="email" class="block text-sm font-medium text-gray-300 mb-1">E-mail</label>
                <input id="email" name="email" type="email" value="{{ old('email') }}" required autofocus class="form-input w-full" placeholder="O seu e-mail">
            </div>
            <div>
                <label for="password" class="block text-sm font-medium text-gray-300 mb-1">Palavra-passe</label>
                <div class="password-field-wrapper relative">
                    <input id="password" name="password" type="password" required autocomplete="current-password" class="form-input w-full pr-12" placeholder="A sua palavra-passe">
                </div>
            </div>
            <div class="flex items-center justify-between">
                <label class="flex items-center gap-2 text-sm text-gray-400">
                    <input type="checkbox" name="remember" class="rounded border-gray-600 bg-dark-bg">
                    Lembrar-me
                </label>
                @if (Route::has('password.request'))
                    <a href="{{ route('password.request') }}" class="text-neon-green text-sm hover:underline">Esqueceu a palavra-passe?</a>
                @endif
            </div>
            <button type="submit" class="btn-primary w-full text-lg">Entrar</button>
        </form>
        <div class="text-center mt-6">
            <p class="text-gray-400">Não tem uma conta? <a href="{{ route('register') }}" class="text-neon-green hover:underline">Registe-se</a></p>
        </div>
        <div class="flex flex-wrap justify-center gap-4 mt-6 text-xs text-gray-500 border-t border-dark-border pt-4">
            <a href="{{ route('legal.terms') }}" class="hover:text-neon-green transition-colors">Termos de Utilização</a>
            <span>•</span>
            <a href="{{ route('legal.privacy') }}" class="hover:text-neon-green transition-colors">Política de Privacidade</a>
        </div>
    </div>
</div>
@endsection
