@extends('layouts.app')

@section('content')
<style>
    :root {
        --primary-color: #6366f1;
        --secondary-color: #8b5cf6;
        --success-color: #10b981;
        --danger-color: #ef4444;
        --warning-color: #f59e0b;
        --dark-bg: #0f172a;
        --card-bg: #1e293b;
        --border-color: #334155;
        --text-primary: #f8fafc;
        --text-secondary: #e2e8f0;
    }

    #crash-game-container {
        max-width: 1200px;
        margin: 2rem auto;
        padding: 0 1rem;
    }

    .game-header {
        text-align: center;
        margin-bottom: 2rem;
        color: var(--text-primary);
    }

    .game-header h1 {
        font-size: 3rem;
        font-weight: 800;
        margin-bottom: 0.5rem;
        background: linear-gradient(45deg, #ff6b6b, #4ecdc4, #45b7d1);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }

    .game-header p {
        font-size: 1.1rem;
        color: var(--text-secondary);
        opacity: 0.8;
    }

    .game-main {
        display: grid;
        grid-template-columns: 1fr 400px;
        gap: 2rem;
        margin-bottom: 2rem;
    }

    #game-area {
        position: relative;
        height: 500px;
        background: linear-gradient(145deg, var(--card-bg), #2d3748);
        border-radius: 20px;
        border: 2px solid var(--border-color);
        display: flex;
        justify-content: center;
        align-items: center;
        overflow: hidden;
        box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
        position: relative;
    }

    #game-area::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: radial-gradient(circle at center, rgba(99, 102, 241, 0.1) 0%, transparent 70%);
        pointer-events: none;
    }

    #multiplier {
        font-size: 6rem;
        font-weight: 900;
        color: var(--success-color);
        text-shadow: 0 0 30px rgba(16, 185, 129, 0.5);
        transition: all 0.3s ease;
        z-index: 10;
        position: relative;
        animation: multiplier-glow 2s ease-in-out infinite alternate;
    }

    @keyframes multiplier-glow {
        from { text-shadow: 0 0 30px rgba(16, 185, 129, 0.5); }
        to { text-shadow: 0 0 50px rgba(16, 185, 129, 0.8); }
    }

    #multiplier.crashed {
        color: var(--danger-color);
        text-shadow: 0 0 30px rgba(239, 68, 68, 0.5);
        animation: shake 0.5s ease-in-out, multiplier-glow-crash 2s ease-in-out infinite alternate;
    }

    @keyframes multiplier-glow-crash {
        from { text-shadow: 0 0 30px rgba(239, 68, 68, 0.5); }
        to { text-shadow: 0 0 50px rgba(239, 68, 68, 0.8); }
    }

    @keyframes shake {
        0%, 100% { transform: translateX(0); }
        25% { transform: translateX(-10px); }
        75% { transform: translateX(10px); }
    }

    .game-controls {
        background: var(--card-bg);
        border-radius: 20px;
        padding: 2rem;
        border: 2px solid var(--border-color);
        box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
    }

    .control-section {
        margin-bottom: 2rem;
    }

    .control-section h3 {
        color: var(--text-primary);
        font-size: 1.2rem;
        font-weight: 600;
        margin-bottom: 1rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .control-section h3::before {
        content: '';
        width: 4px;
        height: 20px;
        background: linear-gradient(45deg, var(--primary-color), var(--secondary-color));
        border-radius: 2px;
    }

    .input-group {
        margin-bottom: 1.5rem;
    }

    .input-group label {
        display: block;
        color: var(--text-secondary);
        font-size: 0.9rem;
        font-weight: 500;
        margin-bottom: 0.5rem;
    }

    .cyber-input {
        width: 100%;
        padding: 1rem;
        background: rgba(255, 255, 255, 0.05);
        border: 2px solid var(--border-color);
        border-radius: 12px;
        color: var(--text-primary);
        font-size: 1rem;
        transition: all 0.3s ease;
        backdrop-filter: blur(10px);
    }

    .cyber-input:focus {
        outline: none;
        border-color: var(--primary-color);
        box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.1);
    }

    .cyber-input::placeholder {
        color: var(--text-secondary);
        opacity: 0.5;
    }

    .button-group {
        display: flex;
        gap: 1rem;
        margin-bottom: 1.5rem;
    }

    .cyber-button {
        flex: 1;
        padding: 1rem 2rem;
        border: none;
        border-radius: 12px;
        font-size: 1rem;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
    }

    .cyber-button:active {
        transform: translateY(1px);
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.2);
    }

    .cyber-button:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.3);
    }

    .cyber-button::after {
        content: '';
        position: absolute;
        top: -50%;
        left: -50%;
        width: 200%;
        height: 200%;
        background: linear-gradient(45deg, transparent, rgba(255, 255, 255, 0.1), transparent);
        transform: rotate(45deg);
        transition: all 0.6s;
        opacity: 0;
    }

    .cyber-button:hover::after {
        opacity: 1;
        transform: rotate(45deg) translate(50%, 50%);
    }

    #bet-btn {
        background: linear-gradient(45deg, var(--primary-color), var(--secondary-color));
        color: white;
    }

    #bet-btn:disabled {
        opacity: 0.6;
        cursor: not-allowed;
        transform: none;
    }

    #cashout-btn {
        background: linear-gradient(45deg, var(--success-color), #059669);
        color: white;
        display: none;
    }

    #cashout-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 25px rgba(16, 185, 129, 0.3);
    }

    #cashout-btn:disabled {
        opacity: 0.6;
        cursor: not-allowed;
        transform: none;
    }

    .game-info {
        background: rgba(255, 255, 255, 0.05);
        border-radius: 12px;
        padding: 1.5rem;
        border: 1px solid var(--border-color);
    }

    .info-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1rem;
        color: var(--text-secondary);
    }

    .info-item:last-child {
        margin-bottom: 0;
    }

    .info-label {
        font-weight: 500;
    }

    .info-value {
        font-weight: 600;
        color: var(--text-primary);
    }

    .quick-bet-buttons {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 0.5rem;
        margin-bottom: 1.5rem;
    }

    .quick-bet-btn {
        padding: 0.75rem;
        background: rgba(255, 255, 255, 0.05);
        border: 1px solid var(--border-color);
        border-radius: 8px;
        color: var(--text-secondary);
        cursor: pointer;
        transition: all 0.3s ease;
        font-weight: 500;
    }

    .quick-bet-btn:hover {
        background: var(--primary-color);
        color: white;
        border-color: var(--primary-color);
    }

    .game-stats {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 1rem;
        margin-top: 2rem;
    }

    .stat-card {
        background: #1e293b;
        border-radius: 12px;
        padding: 1.5rem;
        border: 1px solid #334155;
        text-align: center;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        color: #fff;
    }

    .stat-value {
        font-size: 2rem;
        font-weight: 800;
        color: #fff;
        margin-bottom: 0.5rem;
    }

    .stat-label {
        color: #e2e8f0;
        font-size: 0.9rem;
        font-weight: 500;
    }

    .crash-history {
        margin-top: 2rem;
        background: #1e293b;
        border-radius: 20px;
        padding: 2rem;
        border: 1px solid #334155;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        color: #fff;
    }

    .history-title {
        color: #fff;
        font-size: 1.5rem;
        font-weight: 700;
        margin-bottom: 1.5rem;
        text-align: center;
    }

    .history-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(80px, 1fr));
        gap: 0.5rem;
    }

    .history-item {
        padding: 0.75rem;
        border-radius: 8px;
        text-align: center;
        font-weight: 600;
        font-size: 0.9rem;
        transition: all 0.3s ease;
    }

    .history-item.win {
        background: rgba(16, 185, 129, 0.2);
        color: var(--success-color);
        border: 1px solid rgba(16, 185, 129, 0.3);
    }

    .history-item.lose {
        background: rgba(239, 68, 68, 0.2);
        color: var(--danger-color);
        border: 1px solid rgba(239, 68, 68, 0.3);
    }

    .history-item:hover {
        transform: scale(1.05);
    }

    @media (max-width: 1024px) {
        .game-main {
            grid-template-columns: 1fr;
        }
    }

    @media (max-width: 768px) {
        .game-header h1 {
            font-size: 2rem;
        }
        
        #multiplier {
            font-size: 4rem;
        }
        
        .button-group {
            flex-direction: column;
        }
        
        .quick-bet-buttons {
            grid-template-columns: repeat(2, 1fr);
        }
    }

    /* Animações adicionais */
    .pulse {
        animation: pulse 2s infinite;
    }

    @keyframes pulse {
        0% { transform: scale(1); }
        50% { transform: scale(1.05); }
        100% { transform: scale(1); }
    }

    .glow {
        animation: glow 2s ease-in-out infinite alternate;
    }

    @keyframes glow {
        from { box-shadow: 0 0 20px rgba(99, 102, 241, 0.5); }
        to { box-shadow: 0 0 30px rgba(99, 102, 241, 0.8); }
    }

    /* Efeitos de partículas */
    .particles {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        pointer-events: none;
        overflow: hidden;
    }

    .particle {
        position: absolute;
        width: 4px;
        height: 4px;
        background: var(--success-color);
        border-radius: 50%;
        animation: float 3s ease-in-out infinite;
    }

    @keyframes float {
        0%, 100% { transform: translateY(0px) rotate(0deg); opacity: 1; }
        50% { transform: translateY(-20px) rotate(180deg); opacity: 0.5; }
    }

    /* Efeito de confete para vitória */
    .confetti {
        position: fixed;
        width: 10px;
        height: 10px;
        background: var(--success-color);
        animation: confetti-fall 3s linear forwards;
        z-index: 9999;
    }

    @keyframes confetti-fall {
        0% { transform: translateY(-100vh) rotate(0deg); opacity: 1; }
        100% { transform: translateY(100vh) rotate(720deg); opacity: 0; }
    }

    /* Efeito de explosão para crash */
    .explosion {
        position: absolute;
        width: 100%;
        height: 100%;
        pointer-events: none;
    }

    .explosion-particle {
        position: absolute;
        width: 8px;
        height: 8px;
        background: var(--danger-color);
        border-radius: 50%;
        animation: explode 1s ease-out forwards;
    }

    @keyframes explode {
        0% { transform: scale(0) translate(0, 0); opacity: 1; }
        100% { transform: scale(1) translate(var(--x), var(--y)); opacity: 0; }
    }

    /* Efeito de loading */
    .loading-dots {
        display: inline-block;
    }

    .loading-dots::after {
        content: '';
        animation: dots 1.5s steps(5, end) infinite;
    }

    @keyframes dots {
        0%, 20% { content: ''; }
        40% { content: '.'; }
        60% { content: '..'; }
        80%, 100% { content: '...'; }
    }
</style>

<div id="crash-game-container">
    <div class="game-header">
        <h1>🚀 JOGO DO CRASH</h1>
        <p>Até onde consegues multiplicar antes do crash?</p>
    </div>

    <div class="game-main">
    <div id="game-area">
        <div id="multiplier">1.00x</div>
        </div>

        <div class="game-controls">
            <div class="control-section">
                <h3 style="color: #FFFFFF;">💰 Valor da Aposta</h3>
                <div class="input-group">
                    <label for="bet-amount">Valor (€)</label>
                    <input type="number" id="bet-amount" class="cyber-input" placeholder="10.00" value="10" min="1" step="0.01">
                </div>
                
                <div class="quick-bet-buttons">
                    <button class="quick-bet-btn" onclick="setBetAmount(5)">€ 5</button>
                    <button class="quick-bet-btn" onclick="setBetAmount(10)">€ 10</button>
                    <button class="quick-bet-btn" onclick="setBetAmount(25)">€ 25</button>
                    <button class="quick-bet-btn" onclick="setBetAmount(50)">€ 50</button>
                    <button class="quick-bet-btn" onclick="setBetAmount(100)">€ 100</button>
                    <button class="quick-bet-btn" onclick="setBetAmount(500)">€ 500</button>
                </div>
            </div>

            <div class="control-section">
                <h3>🎮 Controlos</h3>
                <div class="button-group">
                    <button id="bet-btn" class="cyber-button">🎯 APOSTAR</button>
                    <button id="cashout-btn" class="cyber-button">💰 RETIRAR</button>
                </div>
            </div>

            <div class="game-info">
                <div class="info-item">
                    <span class="info-label">Hash do Jogo:</span>
                    <span class="info-value" id="game-hash">-</span>
                </div>
                <div class="info-item">
                    <span class="info-label">Estado:</span>
                    <span class="info-value" id="game-status">A aguardar...</span>
                </div>
            </div>
        </div>
    </div>

    <div class="game-stats">
        <div class="stat-card">
            <div class="stat-value" id="total-bets">0</div>
            <div class="stat-label">Total de Apostas</div>
        </div>
        <div class="stat-card">
            <div class="stat-value" id="total-wins">€ 0</div>
            <div class="stat-label">Ganhos Totais</div>
        </div>
    </div>

    <div class="crash-history">
        <h3 class="history-title">📊 Histórico de Jogos</h3>
        <div class="history-grid" id="history-grid">
            <!-- Histórico será preenchido dinamicamente -->
        </div>
    </div>
</div>

<!-- Toast Customizado -->
<div id="toast-container" style="position: fixed; top: 32px; right: 32px; z-index: 9999; display: none;">
    <div id="toast-message" class="px-6 py-4 rounded-lg shadow-lg font-semibold text-white flex items-center gap-4 animate__animated animate__fadeInDown"
         style="background: linear-gradient(90deg, #00FFB2 0%, #FF005C 100%); min-width: 260px; max-width: 350px;">
        <span id="toast-icon">⚠️</span>
        <span id="toast-text"></span>
        <button onclick="hideToast()" style="margin-left:auto; background:rgba(0,0,0,0.2); border:none; color:white; font-size:1.2rem; border-radius:4px; padding:0 8px; cursor:pointer;">&times;</button>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', async () => {
        // First, ensure we have a fresh CSRF cookie from Sanctum
        try {
            await fetch('/sanctum/csrf-cookie');
        } catch (error) {
            console.error('Could not fetch CSRF cookie:', error);
            showToast('Ocorreu um erro de segurança. Por favor, atualize a página e tente novamente.');
            return;
        }

        const betBtn = document.getElementById('bet-btn');
        const cashoutBtn = document.getElementById('cashout-btn');
        const multiplierEl = document.getElementById('multiplier');
        const gameHashEl = document.getElementById('game-hash');
        const betAmountInput = document.getElementById('bet-amount');
        const gameStatusEl = document.getElementById('game-status');
        const totalBetsEl = document.getElementById('total-bets');
        const totalWinsEl = document.getElementById('total-wins');
        const historyGrid = document.getElementById('history-grid');

        let currentGameId = null;
        let currentBetId = null;
        let crashPoint = null;
        let currentMultiplier = 1.00;
        let gameInterval = null;
        let hasCashedOut = false;
        let totalBets = 0;
        let totalWins = 0;
        let gameHistory = [];

        // Função para definir valor da aposta
        window.setBetAmount = function(amount) {
            betAmountInput.value = amount;
            betAmountInput.focus();
        };

        betBtn.addEventListener('click', async () => {
            const betAmount = parseFloat(betAmountInput.value);
            if (isNaN(betAmount) || betAmount <= 0) {
                showToast('Por favor, insira um valor de aposta válido.');
                return;
            }

            betBtn.disabled = true;
            gameStatusEl.textContent = 'A apostar...';
            addLoadingEffect(gameStatusEl);

            try {
                const response = await fetch('/api/crash/bet', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest',
                    },
                    body: JSON.stringify({ bet_amount: betAmount }),
                    credentials: 'include',
                });

                const data = await response.json();

                if (!response.ok) {
                    throw new Error(data.message || 'Não foi possível iniciar o jogo.');
                }
                
                currentGameId = data.game_id;
                currentBetId = data.bet_id;
                gameHashEl.textContent = data.hash.substring(0, 16) + '...';
                totalBets++;

                // Fetch the crash point for simulation
                const gameResponse = await fetch(`/api/crash/${currentGameId}`, {
                     headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest',
                    },
                    credentials: 'include',
                });
                const gameData = await gameResponse.json();
                
                if (gameData && gameData.crash_point) {
                    crashPoint = parseFloat(gameData.crash_point);
                } else {
                    throw new Error('Não foi possível obter o ponto de crash do servidor.');
                }

                betBtn.style.display = 'none';
                cashoutBtn.style.display = 'block';
                gameStatusEl.textContent = 'A jogar...';
                removeLoadingEffect(gameStatusEl);
                
                // Criar partículas flutuantes
                createParticles();
                
                startVisualMultiplier();

            } catch (error) {
                console.error('Erro ao apostar:', error);
                showToast(error.message);
                removeLoadingEffect(gameStatusEl);
                resetGame();
            }
        });

        cashoutBtn.addEventListener('click', async () => {
            if (!gameInterval || !currentGameId || !currentBetId) {
                console.error('Faltam dados necessários para o cashout:', {
                    gameInterval: !!gameInterval,
                    currentGameId,
                    currentBetId
                });
                return;
            }

            cashoutBtn.disabled = true;
            gameStatusEl.textContent = 'A processar...';
            addLoadingEffect(gameStatusEl);

            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
            if (!csrfToken) {
                showToast('Erro de segurança. Por favor, recarregue a página.');
                cashoutBtn.disabled = false;
                return;
            }

            const requestData = {
                bet_id: currentBetId,
                multiplier: currentMultiplier
            };

            console.log('A enviar pedido de cashout:', requestData);

            try {
                const response = await fetch(`/api/crash/${currentGameId}/cashout`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest',
                    },
                    body: JSON.stringify(requestData),
                    credentials: 'include',
                });

                const data = await response.json();

                console.log('Resposta do cashout:', {
                    status: response.status,
                    data: data
                });

                if (!response.ok) {
                    console.error('O cashout falhou:', data);
                    throw new Error(data.error || data.message || 'Falha no cashout.');
                }
                
                hasCashedOut = true;
                clearInterval(gameInterval);
                
                // Adicionar efeito de brilho no botão
                cashoutBtn.classList.add('glow');
                
                const winnings = parseFloat(data.winnings);
                totalWins += winnings;
                
                // Adicionar ao histórico
                addToHistory(currentMultiplier, 'win');
                
                showToast(`🎉 Retiraste em ${currentMultiplier.toFixed(2)}x! Ganhaste: € ${winnings.toFixed(2)}`);
                
                resetGame();

            } catch (error) {
                 console.error('Erro no cashout:', error);
                 showToast(error.message);
                 cashoutBtn.disabled = false;
                 gameStatusEl.textContent = 'A jogar...';
                 removeLoadingEffect(gameStatusEl);
            }
        });

        function startVisualMultiplier() {
            hasCashedOut = false;
            currentMultiplier = 1.00;
            let startTime = performance.now();
            
            gameInterval = setInterval(() => {
                const elapsedTime = (performance.now() - startTime) / 1000;
                currentMultiplier = Math.pow(1.06, elapsedTime * 2);

                multiplierEl.textContent = `${currentMultiplier.toFixed(2)}x`;

                // Adicionar efeito de vibração no botão de cashout quando multiplicador estiver alto
                if (currentMultiplier >= 2.0 && !cashoutBtn.classList.contains('pulse')) {
                    cashoutBtn.classList.add('pulse');
                } else if (currentMultiplier < 2.0 && cashoutBtn.classList.contains('pulse')) {
                    cashoutBtn.classList.remove('pulse');
                }

                if (currentMultiplier >= crashPoint) {
                    clearInterval(gameInterval);
                    multiplierEl.classList.add('crashed');
                    multiplierEl.textContent = `💥 CRASHOU @ ${crashPoint.toFixed(2)}x`;
                    
                    // Criar efeito de explosão
                    createExplosion(multiplierEl);
                    
                    if(!hasCashedOut){
                        addToHistory(crashPoint, 'lose');
                        showToast('💥 Perdeste! O jogo crashou.');
                    }
                    setTimeout(resetGame, 3000);
                }
            }, 100);
        }

        function resetGame() {
            betBtn.disabled = false;
            betBtn.style.display = 'block';
            cashoutBtn.style.display = 'none';
            cashoutBtn.disabled = false;
            cashoutBtn.classList.remove('pulse', 'glow');
            multiplierEl.classList.remove('crashed');
            multiplierEl.textContent = '1.00x';
            gameHashEl.textContent = '-';
            gameStatusEl.textContent = 'A aguardar...';
            removeLoadingEffect(gameStatusEl);
            
            // Limpar partículas
            const particles = document.querySelector('.particles');
            if (particles) {
                particles.remove();
            }
            
            currentGameId = null;
            currentBetId = null;
            crashPoint = null;
            gameInterval = null;
            
            updateStats();
        }

        function addToHistory(multiplier, result) {
            const historyItem = document.createElement('div');
            historyItem.className = `history-item ${result}`;
            historyItem.textContent = `${multiplier.toFixed(2)}x`;
            
            historyGrid.insertBefore(historyItem, historyGrid.firstChild);
            
            // Manter apenas os últimos 20 itens
            if (historyGrid.children.length > 20) {
                historyGrid.removeChild(historyGrid.lastChild);
            }
        }

        function updateStats() {
            totalBetsEl.textContent = totalBets;
            totalWinsEl.textContent = `€ ${totalWins.toFixed(2)}`;
        }

        function showToast(message, type = 'error') {
            const toast = document.getElementById('toast-container');
            const text = document.getElementById('toast-text');
            const icon = document.getElementById('toast-icon');
            text.textContent = message;
            icon.textContent = type === 'success' ? '✅' : '⚠️';
            toast.style.display = 'block';
            toast.classList.remove('animate__fadeOutUp');
            toast.classList.add('animate__fadeInDown');
            setTimeout(hideToast, 4000);
        }

        function hideToast() {
            const toast = document.getElementById('toast-container');
            toast.classList.remove('animate__fadeInDown');
            toast.classList.add('animate__fadeOutUp');
            setTimeout(() => { toast.style.display = 'none'; }, 500);
        }

        // Função para criar efeito de confete
        function createConfetti() {
            const colors = ['#10b981', '#3b82f6', '#8b5cf6', '#f59e0b', '#ef4444'];
            const confettiCount = 50;
            
            for (let i = 0; i < confettiCount; i++) {
                const confetti = document.createElement('div');
                confetti.className = 'confetti';
                confetti.style.left = Math.random() * 100 + 'vw';
                confetti.style.backgroundColor = colors[Math.floor(Math.random() * colors.length)];
                confetti.style.animationDelay = Math.random() * 3 + 's';
                confetti.style.animationDuration = (Math.random() * 2 + 2) + 's';
                document.body.appendChild(confetti);
                
                setTimeout(() => {
                    confetti.remove();
                }, 5000);
            }
        }

        // Função para criar efeito de explosão
        function createExplosion(element) {
            const explosion = document.createElement('div');
            explosion.className = 'explosion';
            const rect = element.getBoundingClientRect();
            explosion.style.left = rect.left + 'px';
            explosion.style.top = rect.top + 'px';
            explosion.style.width = rect.width + 'px';
            explosion.style.height = rect.height + 'px';
            document.body.appendChild(explosion);
            
            const particleCount = 20;
            for (let i = 0; i < particleCount; i++) {
                const particle = document.createElement('div');
                particle.className = 'explosion-particle';
                const angle = (i / particleCount) * 360;
                const distance = 100 + Math.random() * 50;
                const x = Math.cos(angle * Math.PI / 180) * distance;
                const y = Math.sin(angle * Math.PI / 180) * distance;
                particle.style.setProperty('--x', x + 'px');
                particle.style.setProperty('--y', y + 'px');
                explosion.appendChild(particle);
            }
            
            setTimeout(() => {
                explosion.remove();
            }, 1000);
        }

        // Função para criar partículas flutuantes
        function createParticles() {
            const gameArea = document.getElementById('game-area');
            const particles = document.createElement('div');
            particles.className = 'particles';
            gameArea.appendChild(particles);
            
            const particleCount = 15;
            for (let i = 0; i < particleCount; i++) {
                const particle = document.createElement('div');
                particle.className = 'particle';
                particle.style.left = Math.random() * 100 + '%';
                particle.style.top = Math.random() * 100 + '%';
                particle.style.animationDelay = Math.random() * 3 + 's';
                particle.style.animationDuration = (Math.random() * 2 + 2) + 's';
                particles.appendChild(particle);
            }
        }

        // Função para adicionar efeito de loading
        function addLoadingEffect(element) {
            element.classList.add('loading-dots');
        }

        function removeLoadingEffect(element) {
            element.classList.remove('loading-dots');
        }

        // Adicionar CSS para animação
        const style = document.createElement('style');
        style.textContent = `
            @keyframes slideIn {
                from { transform: translateX(100%); opacity: 0; }
                to { transform: translateX(0); opacity: 1; }
            }
        `;
        document.head.appendChild(style);

        // Inicializar estatísticas
        updateStats();
    });
</script>
@endsection 