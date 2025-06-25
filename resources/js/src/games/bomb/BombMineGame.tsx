import React, { useState, useEffect } from 'react';

const BOARD_SIZE = 5;
// ... existing code ...
                            </div>
                        </div>

                        <div className="control-section">
                            <h3>Ações</h3>
                            {gameOver ? (
                                <button onClick={resetGame} className="cyber-button" style={{ background: colors.primary }}>
                                    Jogar Novamente
                                </button>
                            ) : (
                                <button onClick={cashOut} className="cyber-button" style={{ background: colors.success }} disabled={revealedCount === 0}>
                                    Retirar €{potentialWin.toFixed(2)}
                                </button>
                            )}
                        </div>

                        <div className="control-section">
                            <h3>Informação</h3>
                            <div className="placeholder-text">
                                <p>Multiplicador: {multiplier.toFixed(2)}x</p>
                                <p>Gemas encontradas: {revealedCount}</p>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </>
    );
} 