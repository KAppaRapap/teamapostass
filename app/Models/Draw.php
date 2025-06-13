<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Draw extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'game_id',
        'draw_number',
        'draw_date',
        'jackpot_amount',
        'winning_numbers',
        'additional_info',
        'is_completed',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'winning_numbers' => 'array',
        'additional_info' => 'array',
        'draw_date' => 'datetime',
        'is_completed' => 'boolean',
    ];

    /**
     * Get the game associated with this draw
     */
    public function game()
    {
        return $this->belongsTo(Game::class);
    }

    /**
     * Get the betting slips for this draw
     */
    public function bettingSlips()
    {
        return $this->hasMany(BettingSlip::class);
    }

    /**
     * Check if the draw is in the future
     */
    public function isFuture()
    {
        return $this->draw_date->isFuture();
    }

    /**
     * Check if the draw is past
     */
    public function isPast()
    {
        return $this->draw_date->isPast();
    }

    /**
     * Get the formatted winning numbers
     */
    public function getFormattedWinningNumbersAttribute()
    {
        if (!$this->winning_numbers) {
            return null;
        }

        // Ajuste para lidar com diferentes formatos de 'winning_numbers'
        if (is_array($this->winning_numbers)) {
            if (isset($this->winning_numbers['numbers']) && isset($this->winning_numbers['stars'])) { // Euromilhões
                return 'Números: ' . implode(', ', $this->winning_numbers['numbers']) . ' - Estrelas: ' . implode(', ', $this->winning_numbers['stars']);
            }
            return implode(', ', $this->winning_numbers); // Para Totoloto, Totobola
        }
        return $this->winning_numbers; // Caso seja uma string simples (improvável com o cast)
    }

    /**
     * Gera números vencedores com base no tipo de jogo.
     * Este método é privado e usado internamente.
     */
    private function generateWinningNumbersForGame()
    {
        if (!$this->game) { // Certifica-se de que a relação game foi carregada
            $this->load('game');
        }

        switch ($this->game->type) {
            case 'Euromilhões':
                $numbers = collect(range(1, 50))->shuffle()->take(5)->sort()->values()->all();
                $stars = collect(range(1, 12))->shuffle()->take(2)->sort()->values()->all();
                return ['numbers' => $numbers, 'stars' => $stars];
            case 'Totoloto':
                return collect(range(1, 49))->shuffle()->take(6)->sort()->values()->all();
            case 'Totobola':
                $results = [];
                for ($i = 0; $i < 13; $i++) {
                    $results[] = collect(['1', 'X', '2'])->random();
                }
                return $results;
            case 'Placard':
                return ['message' => 'Resultados do Placard são baseados em eventos reais e não são gerados automaticamente aqui.'];
            default:
                return ['error' => 'Tipo de jogo desconhecido para geração de resultados: ' . $this->game->type];
        }
    }

    /**
     * Processa o sorteio se já passou a data e hora do resultado (encerra e gera números).
     * A hora do resultado é considerada 1 hora após draw_date.
     */
    public function processIfDue()
    {
        // Considera a hora do resultado como 1 hora após a draw_date
        $resultTime = $this->draw_date->copy()->addHour();

        if (!$this->is_completed && $resultTime->isPast()) {
            $this->is_completed = true;
            if (empty($this->winning_numbers)) { // Só gera se não houver números (para não sobrescrever se já existirem)
                $this->winning_numbers = $this->generateWinningNumbersForGame();
            }
            $this->save();

            // Lógica para atualizar apostas (betting slips) pode ser adicionada aqui ou em um evento/listener separado
            // Exemplo básico (você precisará adaptar ao seu sistema de prêmios):
            // foreach ($this->bettingSlips as $slip) {
            //     $matchingCount = is_array($slip->numbers) && is_array($this->winning_numbers)
            //         ? count(array_intersect($slip->numbers, $this->winning_numbers['numbers'] ?? $this->winning_numbers)) // Adapte a chave para Euromilhões
            //         : 0;
            //     // Lógica de prêmios...
            //     // $slip->update([...]);
            // }
        }
    }
}
