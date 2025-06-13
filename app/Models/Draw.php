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

        return implode(', ', $this->winning_numbers);
    }

    /**
     * Fecha automaticamente o sorteio e gera números se a data já passou.
     */
    public function autoCloseIfNeeded()
    {
        if (!$this->is_completed && $this->draw_date->isPast()) {
            $this->is_completed = true;
            // Gerar 6 números de 1 a 49 (ajuste conforme o jogo)
            $this->winning_numbers = collect(range(1,49))->shuffle()->take(6)->sort()->values()->all();
            $this->save();
        }
    }

    /**
     * Processa o sorteio se já passou a data (encerra e gera números).
     */
    public function processIfDue()
    {
        if (!$this->is_completed && $this->draw_date->isPast()) {
            $this->is_completed = true;
            if (empty($this->winning_numbers)) {
                $this->winning_numbers = collect(range(1,49))->shuffle()->take(6)->sort()->values()->all();
            }
            $this->save();

            // Atualiza cada aposta associada ao sorteio
            foreach ($this->bettingSlips as $slip) {
                $matchingCount = is_array($slip->numbers) && is_array($this->winning_numbers)
                    ? count(array_intersect($slip->numbers, $this->winning_numbers))
                    : 0;
                switch ($matchingCount) {
                    case 3: $prize = 5.00; break;
                    case 4: $prize = 50.00; break;
                    case 5: $prize = 500.00; break;
                    case 6: $prize = 5000.00; break;
                    default: $prize = 0.00; break;
                }
                $slip->update([
                    'winnings' => $prize,
                    'has_won' => $prize > 0,
                    'is_checked' => true,
                ]);
            }
        }
    }

    protected static function booted()
    {
        static::updated(function ($draw) {
            if ($draw->isDirty('is_completed') && $draw->is_completed) {
                Activity::logDrawCompleted($draw);
            }
        });
    }
}
