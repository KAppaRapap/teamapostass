@extends('layouts.app')

@section('content')
<div class="flex justify-center items-center min-h-[60vh] py-8">
    <div class="bg-gray-900 border border-gray-800 rounded-lg shadow-lg p-8 w-full max-w-md">
        <h2 class="text-2xl font-bold text-white mb-6 text-center">Depositar na Carteira Virtual</h2>
        @if ($errors->any())
            <div class="bg-red-600 text-white rounded p-3 mb-4">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        <form method="POST" action="{{ route('wallet.deposit.submit') }}" class="space-y-4">
            @csrf
            <div>
                <label for="amount" class="block text-gray-200 font-bold mb-2">Valor do Depósito</label>
                <input type="number" min="1" step="0.01" class="w-full border border-gray-700 bg-gray-800 text-white rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-neon-green" id="amount" name="amount" required autofocus>
            </div>
            <button type="submit" class="w-full bg-neon-green hover:bg-green-600 text-dark-bg font-bold py-2 px-4 rounded transition flex items-center justify-center gap-2">
                <i class="fas fa-plus"></i> Depositar
            </button>
            <a href="{{ route('dashboard') }}" class="w-full block text-center text-neon-green hover:text-white mt-2">Voltar ao Dashboard</a>
        </form>
    </div>
</div>
@endsection
