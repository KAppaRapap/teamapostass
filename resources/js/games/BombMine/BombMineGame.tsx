import React, { useState, useEffect } from 'react';

// Declarações TypeScript para funções globais
declare global {
    interface Window {
        updateBalanceAfterBet?: () => void;
    }
}

const BOARD_SIZE = 5;

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

// Função para calcular o multiplicador baseado no número de bombas
const calculateMultiplier = (numBombs: number, revealedCount: number): number => {
    const totalCells = BOARD_SIZE * BOARD_SIZE;
    const safeCells = totalCells - numBombs;
    if (revealedCount === 0) return 1.0;
    if (revealedCount >= safeCells) {
        // Não pode dividir por zero ou negativo, retorna apenas o multiplicador base
        return 1 + (numBombs * 0.1);
    }
    // Multiplicador base mais alto para mais bombas
    const baseMultiplier = 1 + (numBombs * 0.1); // Mais bombas = multiplicador base maior
    // Multiplicador progressivo baseado nas células reveladas
    const progressiveMultiplier = (safeCells / (safeCells - revealedCount)) * 0.95;
    return baseMultiplier * progressiveMultiplier;
};

const styles = `
    .bomb-game-container {
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
        background: linear-gradient(45deg, ${colors.warning}, ${colors.danger});
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
    }
    .game-area {
        background: ${colors.cardBg};
        border-radius: 20px;
        padding: 2rem;
        border: 2px solid ${colors.border};
    }
    .board-grid {
        display: grid;
        grid-template-columns: repeat(${BOARD_SIZE}, 1fr);
        gap: 0.5rem;
    }
    .cell {
        aspect-ratio: 1 / 1;
        border-radius: 8px;
        cursor: pointer;
        transition: all 0.3s ease;
        background: rgba(255, 255, 255, 0.05);
        border: 1px solid ${colors.border};
        font-size: 1.5rem;
        display: flex;
        justify-content: center;
        align-items: center;
    }
    .cell:not(.revealed):hover {
        background: rgba(255, 255, 255, 0.1);
    }
    .cell.revealed.bomb {
        background-color: ${colors.danger};
        animation: shake 0.5s;
    }
    .cell.revealed.safe {
        background-color: ${colors.success};
    }
    @keyframes shake {
        0%, 100% { transform: translateX(0); }
        25% { transform: translateX(-5px); }
        75% { transform: translateX(5px); }
    }
    .game-controls {
        background: ${colors.cardBg};
        border-radius: 20px;
        padding: 2rem;
        border: 2px solid ${colors.border};
    }
    .control-section {
        margin-bottom: 1.5rem;
    }
    .control-section h3 {
        color: ${colors.textPrimary};
        margin-bottom: 0.5rem;
        font-size: 1.1rem;
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
    .cyber-select {
        width: 100%;
        padding: 1rem;
        background: ${colors.cardBg};
        border: 2px solid ${colors.border};
        border-radius: 12px;
        color: ${colors.textPrimary};
        font-size: 1rem;
        appearance: none;
        -webkit-appearance: none;
        -moz-appearance: none;
    }
    .cyber-select option {
        background: ${colors.cardBg};
        color: ${colors.textPrimary};
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
        transition: all 0.2s ease;
    }
    .quick-bet-btn:hover {
        background: rgba(255, 255, 255, 0.1);
    }
    .quick-bet-btn:disabled {
        opacity: 0.5;
        cursor: not-allowed;
    }
    .cyber-button {
        width: 100%;
        padding: 1rem 2rem;
        border: none;
        border-radius: 12px;
        font-size: 1rem;
        font-weight: 600;
        cursor: pointer;
        color: white;
        transition: all 0.2s ease;
    }
    .cyber-button:hover {
        transform: translateY(-2px);
    }
    .placeholder-text {
        text-align: center;
        color: ${colors.textSecondary};
        opacity: 0.8;
    }
    .bomb-info {
        background: rgba(255, 255, 255, 0.05);
        border-radius: 8px;
        padding: 1rem;
        margin-bottom: 1rem;
        border: 1px solid ${colors.border};
    }
    .bomb-info h4 {
        color: ${colors.warning};
        margin-bottom: 0.5rem;
    }
    .bomb-info p {
        color: ${colors.textSecondary};
        font-size: 0.9rem;
        margin: 0.25rem 0;
    }
    .max-multiplier {
        color: ${colors.success};
        font-weight: bold;
    }
`;

interface Cell {
    isBomb: boolean;
    isRevealed: boolean;
}

function generateBoard(numBombs: number): Cell[][] {
    const board: Cell[][] = Array(BOARD_SIZE).fill(null).map(() => Array(BOARD_SIZE).fill(null).map(() => ({ isBomb: false, isRevealed: false })));
    let bombsPlaced = 0;
    while (bombsPlaced < numBombs) {
        const row = Math.floor(Math.random() * BOARD_SIZE);
        const col = Math.floor(Math.random() * BOARD_SIZE);
        if (!board[row][col].isBomb) {
            board[row][col].isBomb = true;
            bombsPlaced++;
        }
    }
    return board;
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

export default function BombMineGame() {
    const [board, setBoard] = useState<Cell[][]>([]);
    const [betAmount, setBetAmount] = useState('10');
    const [numBombs, setNumBombs] = useState(3);
    const [gameOver, setGameOver] = useState(false);
    const [revealedCount, setRevealedCount] = useState(0);
    const [multiplier, setMultiplier] = useState(1.0);
    const [potentialWin, setPotentialWin] = useState(0);
    const [isBetPlaced, setIsBetPlaced] = useState(false);
    const [bettingSlipId, setBettingSlipId] = useState<number | null>(null);

    // Inicializar o tabuleiro quando o número de bombas mudar
    useEffect(() => {
        setBoard(generateBoard(numBombs));
        setGameOver(false);
        setRevealedCount(0);
        setIsBetPlaced(false);
    }, [numBombs]);

    useEffect(() => {
        if (revealedCount > 0) {
            const currentMultiplier = calculateMultiplier(numBombs, revealedCount);
            setMultiplier(currentMultiplier);
            setPotentialWin(parseFloat(betAmount) * currentMultiplier);
        } else {
            setMultiplier(1.0);
            setPotentialWin(0);
        }
    }, [revealedCount, betAmount, numBombs]);

    // Função para registrar aposta no backend
    const placeBet = async () => {
        try {
            const res = await fetch('/api/games/bet', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': getCsrfToken(),
                },
                body: JSON.stringify({ amount: parseFloat(betAmount), game: 'BombMine' })
            });

            if (!res.ok) throw new Error('Erro ao registrar aposta');

            const data = await res.json();
            setBettingSlipId(data.betting_slip_id);
            setIsBetPlaced(true);

            // Atualizar saldo automaticamente
            if (window.updateBalanceAfterBet) {
                window.updateBalanceAfterBet();
            }

        } catch (e) {
            alert('Erro ao registrar aposta: saldo insuficiente ou não autenticado.');
        }
    };

    // Função para registrar resultado no backend
    const registerResult = async (amount: number, won: boolean) => {
        try {
            const res = await fetch('/api/games/win', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': getCsrfToken(),
                },
                body: JSON.stringify({
                    amount,
                    game: 'BombMine',
                    won,
                    betting_slip_id: bettingSlipId
                })
            });

            if (res.ok) {
                const data = await res.json();

                // Atualizar saldo automaticamente
                if (window.updateBalanceAfterBet) {
                    window.updateBalanceAfterBet();
                }
            }
        } catch (e) {
            console.error('Erro ao registrar resultado:', e);
        }
    };

    const revealCell = (row: number, col: number) => {
        if (!isBetPlaced) {
            placeBet();
            return;
        }
        if (gameOver || board[row][col].isRevealed || !betAmount || parseFloat(betAmount) <= 0) return;

        const newBoard = board.map(r => r.slice());
        newBoard[row][col].isRevealed = true;

        if (newBoard[row][col].isBomb) {
            setGameOver(true);
            registerResult(0, false); // Perdeu
            // Revelar todas as bombas
            for (let r = 0; r < BOARD_SIZE; r++) {
                for (let c = 0; c < BOARD_SIZE; c++) {
                    if (newBoard[r][c].isBomb) newBoard[r][c].isRevealed = true;
                }
            }
        } else {
            setRevealedCount(prev => prev + 1);
        }
        setBoard(newBoard);
    };

    const resetGame = () => {
        setBoard(generateBoard(numBombs));
        setGameOver(false);
        setRevealedCount(0);
        setIsBetPlaced(false);
    };
    
    const cashOut = async () => {
        if (revealedCount > 0) {
            await registerResult(potentialWin, true); // Ganhou
            showToast(`Ganhou €${potentialWin.toFixed(2)}!`);
            resetGame();
        }
    };

    // Calcular multiplicador máximo possível
    const safeCells = BOARD_SIZE * BOARD_SIZE - numBombs;
    const maxMultiplier = safeCells > 0 ? calculateMultiplier(numBombs, safeCells - 1) : 1 + (numBombs * 0.1);

    return (
        <>
            <style>{styles}</style>
            <div className="bomb-game-container">
                <div className="game-header">
                    <h1>💣 Campo Minado</h1>
                    <p style={{color: '#fff'}}>Escolha o número de bombas e encontre os locais seguros para multiplicar a sua aposta.</p>
                </div>
                <div className="game-main">
                    <div className="game-area">
                        <div className="board-grid">
                            {board.map((row, rIndex) =>
                                row.map((cell, cIndex) => (
                                    <div
                                        key={`${rIndex}-${cIndex}`}
                                        className={`cell ${cell.isRevealed ? (cell.isBomb ? 'revealed bomb' : 'revealed safe') : ''}`}
                                        onClick={() => revealCell(rIndex, cIndex)}
                                    >
                                        {cell.isRevealed ? (cell.isBomb ? '💣' : '💎') : ''}
                                    </div>
                                ))
                            )}
                        </div>
                    </div>
                    <div className="game-controls">
                        <div className="control-section">
                            <h3>Número de Bombas</h3>
                            <select
                                value={numBombs}
                                onChange={(e) => setNumBombs(parseInt(e.target.value))}
                                className="cyber-select"
                                style={{ background: colors.cardBg, color: colors.textPrimary }}
                                disabled={revealedCount > 0 || isBetPlaced}
                            >
                                {Array.from({length: 24}, (_, i) => i + 1).map(num => (
                                    <option key={num} value={num}>
                                        {num} {num === 1 ? 'Bomba' : 'Bombas'}
                                    </option>
                                ))}
                            </select>
                            <div className="bomb-info">
                                <h4>ℹ️ Informações</h4>
                                <p>• Mais bombas = Maior risco</p>
                                <p>• Multiplicador máximo: <span className="max-multiplier">{maxMultiplier.toFixed(2)}x</span></p>
                                <p>• Células seguras: {BOARD_SIZE * BOARD_SIZE - numBombs}</p>
                            </div>
                        </div>

                        <div className="control-section">
                            <h3>Valor da Aposta</h3>
                            <input
                                type="number"
                                value={betAmount}
                                onChange={(e) => setBetAmount(e.target.value)}
                                className="cyber-input"
                                placeholder="Valor (€)"
                                disabled={revealedCount > 0}
                            />
                            <div className="quick-bet-buttons">
                                {[10, 25, 50, 100, 250, 500].map(amount => (
                                    <button 
                                        key={amount} 
                                        onClick={() => setBetAmount(amount.toString())} 
                                        className="quick-bet-btn" 
                                        disabled={revealedCount > 0}
                                    >
                                        €{amount}
                                    </button>
                                ))}
                            </div>
                        </div>

                        <div className="control-section text-center">
                            <div>Multiplicador: <span className="font-bold text-xl">{multiplier.toFixed(2)}x</span></div>
                            <div>Ganhos Potenciais: <span className="font-bold text-xl text-green-400">€{potentialWin.toFixed(2)}</span></div>
                        </div>
                        
                        {gameOver ? (
                             <button onClick={resetGame} className="cyber-button bg-red-500">Jogar Novamente</button>
                        ) : revealedCount > 0 ? (
                            <button onClick={cashOut} className="cyber-button bg-green-500">Retirar Ganhos</button>
                        ) : (
                            <div className="placeholder-text">Selecione um campo para começar</div>
                        )}
                    </div>
                </div>
            </div>
        </>
    );
} 