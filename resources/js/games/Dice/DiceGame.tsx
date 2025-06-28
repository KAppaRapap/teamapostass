import React, { useState, useEffect } from 'react';

const colors = {
    primary: '#6366f1',
    secondary: '#8b5cf6',
    success: '#10b981',
    danger: '#ef4444',
    warning: '#f59e0b',
    darkBg: '#0f172a',
    cardBg: '#1e293b',
    border: '#334155',
    textPrimary: '#f8fafc',
    textSecondary: '#e2e8f0',
};

const styles = `
    .dice-game-container {
        max-width: 1200px;
        margin: 2rem auto;
        padding: 0 1rem;
        color: ${colors.textPrimary};
    }
    .game-header {
        text-align: center;
        margin-bottom: 2rem;
    }
    .game-header h1 {
        font-size: 3rem;
        font-weight: 800;
        margin-bottom: 0.5rem;
        background: linear-gradient(45deg, ${colors.primary}, ${colors.secondary});
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }
    .game-header p {
        font-size: 1.1rem;
        color: ${colors.textSecondary};
        opacity: 0.8;
    }
    .game-main {
        display: grid;
        grid-template-columns: 1fr 400px;
        gap: 2rem;
        margin-bottom: 2rem;
    }
    .game-area {
        position: relative;
        min-height: 500px;
        background: linear-gradient(145deg, ${colors.cardBg}, #2d3748);
        border-radius: 20px;
        border: 2px solid ${colors.border};
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        overflow: hidden;
        box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
        padding: 2rem;
    }
    .dice-result {
        font-size: 6rem;
        font-weight: 900;
        transition: all 0.3s ease;
    }
    .dice-result.win {
        color: ${colors.success};
        text-shadow: 0 0 30px rgba(16, 185, 129, 0.5);
    }
    .dice-result.lose {
        color: ${colors.danger};
        text-shadow: 0 0 30px rgba(239, 68, 68, 0.5);
    }
    .game-controls {
        background: ${colors.cardBg};
        border-radius: 20px;
        padding: 2rem;
        border: 2px solid ${colors.border};
        box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
    }
    .control-section {
        margin-bottom: 1.5rem;
    }
    .control-section h3 {
        color: ${colors.textPrimary};
        font-size: 1.2rem;
        font-weight: 600;
        margin-bottom: 1rem;
    }
    .cyber-input {
        width: 100%;
        padding: 1rem;
        background: rgba(255, 255, 255, 0.05);
        border: 2px solid ${colors.border};
        border-radius: 12px;
        color: ${colors.textPrimary};
        font-size: 1rem;
    }
    .quick-bet-buttons {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 0.5rem;
        margin-top: 1rem;
    }
    .quick-bet-btn {
        padding: 0.75rem;
        background: rgba(255, 255, 255, 0.05);
        border: 1px solid ${colors.border};
        border-radius: 8px;
        color: ${colors.textSecondary};
        cursor: pointer;
    }
    .cyber-button {
        width: 100%;
        padding: 1rem 2rem;
        border: none;
        border-radius: 12px;
        font-size: 1rem;
        font-weight: 600;
        cursor: pointer;
        background: linear-gradient(45deg, ${colors.primary}, ${colors.secondary});
        color: white;
    }
    .history-grid {
        margin-top: 2rem;
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(60px, 1fr));
        gap: 0.5rem;
    }
    .history-item {
        padding: 0.5rem;
        border-radius: 8px;
        text-align: center;
        font-weight: 600;
    }
    .history-item.win {
        background: rgba(16, 185, 129, 0.2);
        color: ${colors.success};
    }
    .history-item.lose {
        background: rgba(239, 68, 68, 0.2);
        color: ${colors.danger};
    }
    .placeholder-text {
        font-size: 2rem;
        font-weight: 600;
        color: ${colors.textSecondary};
        opacity: 0.8;
    }
`;

interface DiceResult {
  number: number;
  isWin: boolean;
}

// Função utilitária para obter o token CSRF do Laravel
function getCsrfToken() {
    const meta = document.querySelector('meta[name="csrf-token"]');
    return meta ? meta.getAttribute('content') || '' : '';
}

function showToast(message: string) {
    let container = document.getElementById('toast-container');
    if (!container) {
        container = document.createElement('div');
        container.id = 'toast-container';
        document.body.appendChild(container);
    }
    const toast = document.createElement('div');
    toast.className = 'toast-notification';
    toast.innerText = message;
    container.appendChild(toast);
    setTimeout(() => toast.classList.add('show'), 100);
    setTimeout(() => {
        toast.classList.remove('show');
        setTimeout(() => toast.remove(), 400);
    }, 3500);
}

export default function DiceGame() {
  const [betAmount, setBetAmount] = useState('10');
  const [targetNumber, setTargetNumber] = useState(50);
  const [condition, setCondition] = useState<'above' | 'below'>('above');
  const [isRolling, setIsRolling] = useState(false);
  const [currentResult, setCurrentResult] = useState<DiceResult | null>(null);
  const [balance, setBalance] = useState(1000); // Saldo inicial de exemplo
  const [history, setHistory] = useState<DiceResult[]>([]);
  const [multiplier, setMultiplier] = useState(1.98);
  const [isBetPlaced, setIsBetPlaced] = useState(false);
  const [bettingSlipId, setBettingSlipId] = useState<number | null>(null);

  useEffect(() => {
    const probability = condition === 'above' 
      ? (100 - targetNumber) / 100 
      : targetNumber / 100;
    setMultiplier(probability > 0 ? (0.99 / probability) : 1.98);
  }, [targetNumber, condition]);

  useEffect(() => {
    if (currentResult) {
      if (currentResult.isWin) {
        showToast('Ganhaste!');
      } else {
        showToast('Perdeste!');
      }
    }
  }, [currentResult]);

  // Função para registrar aposta no backend
  const placeBet = async () => {
    try {
      const res = await fetch('/api/games/bet', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': getCsrfToken(),
        },
        body: JSON.stringify({ amount: parseFloat(betAmount), game: 'Dice' })
      });
      if (!res.ok) throw new Error('Erro ao registrar aposta');

      const data = await res.json();
      setBettingSlipId(data.betting_slip_id);
      setIsBetPlaced(true);
    } catch (e) {
      alert('Erro ao registrar aposta: saldo insuficiente ou não autenticado.');
    }
  };

  // Função para registrar resultado no backend
  const registerResult = async (amount: number, won: boolean) => {
    await fetch('/api/games/win', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': getCsrfToken(),
      },
      body: JSON.stringify({
        amount,
        game: 'Dice',
        won,
        betting_slip_id: bettingSlipId
      })
    });
  };

  const rollDice = async () => {
    if (!betAmount || parseFloat(betAmount) <= 0) return;
    if (!isBetPlaced) {
      await placeBet();
      return;
    }
    setIsRolling(true);
    setCurrentResult(null);

    setTimeout(async () => {
      const resultNum = Math.random() * 100;
      const isWin = condition === 'above' 
        ? resultNum > targetNumber 
        : resultNum < targetNumber;
      const winAmount = isWin ? parseFloat(betAmount) * multiplier : 0;
      setBalance(prev => prev - parseFloat(betAmount) + winAmount);
      await registerResult(winAmount, isWin);
      const newResult = { number: resultNum, isWin };
      setCurrentResult(newResult);
      setHistory(prev => [newResult, ...prev.slice(0, 9)]);
      setIsBetPlaced(false);
      setIsRolling(false);
    }, 1500);
  };

  return (
    <>
      <style>{styles}</style>
      <div className="dice-game-container">
        <div className="game-header">
          <h1>🎲 Jogo de Dados</h1>
          <p style={{color: '#fff'}}>Acerte se o próximo número será maior ou menor que o alvo.</p>
          
        </div>
        
        <div className="game-main">
          <div className="game-area">
            {isRolling ? (
              <div className="text-4xl font-bold">A rolar...</div>
            ) : currentResult ? (
              <>
                <div className={`dice-result ${currentResult.isWin ? 'win' : 'lose'}`}>
                  {currentResult.number.toFixed(2)}
                </div>
                <div className="mt-4 text-xl">
                  {currentResult.isWin ? 'Ganhaste!' : 'Perdeste!'}
                </div>
              </>
            ) : (
              <div className="placeholder-text">Faça a sua aposta!</div>
            )}
            <div className="history-grid">
              {history.map((item, index) => (
                <div key={index} className={`history-item ${item.isWin ? 'win' : 'lose'}`}>
                  {item.number.toFixed(1)}
                </div>
              ))}
            </div>
          </div>

          <div className="game-controls">
            <div className="control-section">
              <h3 style={{color: '#FFFFFF'}}>Valor da Aposta</h3>
              <input
                type="number"
                value={betAmount}
                onChange={(e) => setBetAmount(e.target.value)}
                className="cyber-input"
                placeholder="Valor (€)"
              />
              <div className="quick-bet-buttons">
                {[10, 25, 50, 100, 250, 500].map(amount => (
                  <button key={amount} onClick={() => setBetAmount(amount.toString())} className="quick-bet-btn">
                    €{amount}
                  </button>
                ))}
              </div>
            </div>

            <div className="control-section">
              <h3>Alvo: {targetNumber}</h3>
              <input
                type="range"
                min="1"
                max="99"
                value={targetNumber}
                onChange={(e) => setTargetNumber(parseInt(e.target.value))}
                className="w-full"
              />
              <div className="flex justify-between text-xs mt-1">
                <span>1</span>
                <span>99</span>
              </div>
            </div>

            <div className="control-section">
              <h3>Condição</h3>
              <div className="flex gap-2">
                <button
                  onClick={() => setCondition('below')}
                  className={`flex-1 p-3 rounded-lg ${condition === 'below' ? 'bg-indigo-500' : 'bg-gray-700'}`}
                >
                  Abaixo de
                </button>
                <button
                  onClick={() => setCondition('above')}
                  className={`flex-1 p-3 rounded-lg ${condition === 'above' ? 'bg-indigo-500' : 'bg-gray-700'}`}
                >
                  Acima de
                </button>
              </div>
            </div>
            
            <div className="control-section text-center">
                <div>Multiplicador: <span className="font-bold text-xl">{multiplier.toFixed(2)}x</span></div>
                <div>Probabilidade: <span className="font-bold">{((condition === 'above' ? (100 - targetNumber) : targetNumber)).toFixed(1)}%</span></div>
            </div>

            <button onClick={rollDice} disabled={isRolling} className="cyber-button">
              {isRolling ? 'A rolar...' : '🎲 Jogar'}
            </button>
          </div>
        </div>
      </div>
    </>
  );
} 