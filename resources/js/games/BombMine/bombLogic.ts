// Funções utilitárias para lógica do BombMine

export function generateBombBoard(size: number, numBombs: number): boolean[] {
  const board = Array(size * size).fill(false);
  let bombs = 0;
  while (bombs < numBombs) {
    const idx = Math.floor(Math.random() * board.length);
    if (!board[idx]) {
      board[idx] = true;
      bombs++;
    }
  }
  return board;
} 