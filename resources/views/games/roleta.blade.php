@extends('layouts.app')

@section('title', 'Roleta')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-6xl mx-auto">
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900 mb-2">🎰 Roleta</h1>
            <p class="text-gray-600">Teste sua sorte na roleta clássica!</p>
        </div>

        <!-- Saldo do Utilizador -->
        <div class="bg-white rounded-lg shadow-md p-6 mb-6">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-xl font-semibold text-gray-800">Seu Saldo</h2>
                    <p class="text-2xl font-bold text-green-600" id="user-balance">
                        R$ {{ number_format(auth()->user()->virtual_balance, 2, ',', '.') }}
                    </p>
                </div>
                <div class="text-right">
                    <p class="text-sm text-gray-500">ID: {{ auth()->id() }}</p>
                    <p class="text-sm text-gray-500">{{ auth()->user()->name }}</p>
                </div>
            </div>
        </div>

        <!-- Container da Roleta -->
        <div class="bg-white rounded-lg shadow-lg p-6">
            <div id="roleta-container" class="flex justify-center">
                <!-- A roleta será renderizada aqui pelo React -->
            </div>
        </div>

        <!-- Instruções -->
        <div class="mt-6 bg-blue-50 rounded-lg p-4">
            <h3 class="text-lg font-semibold text-blue-800 mb-2">Como Jogar:</h3>
            <ul class="text-blue-700 space-y-1">
                <li>• Escolha um número de 0 a 36</li>
                <li>• Defina o valor da sua aposta</li>
                <li>• Clique em "Apostar" e aguarde o resultado</li>
                <li>• Se acertar, ganha 35x o valor apostado!</li>
            </ul>
        </div>
    </div>
</div>

<!-- Scripts -->
<script>
    // Passar dados do utilizador para o React
    window.userData = {
        id: {{ auth()->id() }},
        name: "{{ auth()->user()->name }}",
        balance: {{ auth()->user()->virtual_balance }},
        csrfToken: "{{ csrf_token() }}"
    };
</script>
@endsection 