<!-- Carteira Virtual -->
<div class="content-card mb-8">
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6">
        <h2 class="font-orbitron font-bold text-2xl mb-4 md:mb-0">
            <span class="text-neon-green">Carteira</span> Virtual
        </h2>
        <a href="{{ route('wallet.index') }}" class="btn-outline">
            <i class="fas fa-receipt mr-2"></i> Ver Extrato
        </a>
    </div>
    
    <div class="grid md:grid-cols-2 gap-8">
        <!-- Saldo Disponível -->
        <div class="bg-gradient-to-br from-[#11998e] to-[#38ef7d] rounded-lg p-6">
            <div class="flex items-center mb-4">
                <div class="w-12 h-12 bg-white bg-opacity-20 rounded-lg flex items-center justify-center mr-4">
                    <i class="fas fa-wallet text-2xl text-white"></i>
                </div>
                <div>
                    <p class="text-white text-opacity-80 text-sm">Saldo Disponível</p>
                    <p class="text-3xl font-bold text-white virtual-wallet-balance">
                        €{{ number_format(Auth::user()->virtual_balance, 2) }}
                    </p>
                </div>
            </div>
            
            <div class="flex gap-3">
                <a href="{{ route('wallet.deposit') }}" class="flex-1 bg-white bg-opacity-20 hover:bg-opacity-30 text-white text-center py-2 px-4 rounded-lg transition-all duration-300">
                    <i class="fas fa-plus mr-2"></i> Depositar
                </a>
                <a href="{{ route('wallet.index') }}" class="flex-1 bg-white bg-opacity-20 hover:bg-opacity-30 text-white text-center py-2 px-4 rounded-lg transition-all duration-300">
                    <i class="fas fa-exchange-alt mr-2"></i> Transferir
                </a>
            </div>
        </div>
        
        <!-- Estatísticas -->
        <div class="space-y-4">
            @php
                $totalApostado = Auth::user()->activities()->where('type', 'bet_placed')->sum('data->amount');
                $totalGanhoAmount = Auth::user()->activities()->where('type', 'bet_won')->sum('data->amount');
                $totalGanhoPrize = Auth::user()->activities()->where('type', 'bet_won')->sum('data->prize_amount');
                $totalGanho = $totalGanhoAmount + $totalGanhoPrize;
                $totalMovimentado = $totalApostado + $totalGanho;
                $maiorVitoria = Auth::user()->activities()->where('type', 'bet_won')->max('data->amount');
                $totalApostas = Auth::user()->activities()->where('type', 'bet_placed')->count();
                $totalVitorias = Auth::user()->activities()->where('type', 'bet_won')->count();
                $taxaSucesso = $totalApostas > 0 ? ($totalVitorias / $totalApostas) * 100 : 0;
            @endphp
            <div class="bg-dark-bg border border-dark-border rounded-lg p-4">
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <div class="w-10 h-10 bg-gradient-to-br from-green-500 to-green-600 rounded-lg flex items-center justify-center mr-3">
                            <i class="fas fa-euro-sign text-white"></i>
                        </div>
                        <div>
                            <p class="text-gray-400 text-sm">Total Ganho</p>
                            <p class="font-semibold text-neon-green">
                                €{{ number_format($totalGanho, 2) }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="bg-dark-bg border border-dark-border rounded-lg p-4">
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <div class="w-10 h-10 bg-gradient-to-br from-neon-pink to-pink-600 rounded-lg flex items-center justify-center mr-3">
                            <i class="fas fa-trophy text-white"></i>
                        </div>
                        <div>
                            <p class="text-gray-400 text-sm">Maior Vitória</p>
                            <p class="text-xl font-bold text-white">
                                €{{ number_format($maiorVitoria, 2) }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="bg-dark-bg border border-dark-border rounded-lg p-4">
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <div class="w-10 h-10 bg-gradient-to-br from-purple-500 to-purple-600 rounded-lg flex items-center justify-center mr-3">
                            <i class="fas fa-percentage text-white"></i>
                        </div>
                        <div>
                            <p class="text-gray-400 text-sm">Taxa de Sucesso</p>
                            <p class="text-xl font-bold text-white">
                                {{ number_format($taxaSucesso, 1) }}%
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
