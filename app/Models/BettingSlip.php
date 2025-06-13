<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Validator;

class BettingSlip extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'group_id',
        'draw_id',
        'numbers',
        'stars',
        'predictions',
        'total_cost',
        'bet_type',
        'system_details',
        'status',
        'prize_amount',
        'validation_errors',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'numbers' => 'array',
        'stars' => 'array',
        'predictions' => 'array',
        'system_details' => 'array',
        'validation_errors' => 'array',
        'total_cost' => 'decimal:2',
        'prize_amount' => 'decimal:2',
    ];

    // Constantes para status da aposta
    const STATUS_PENDING = 'pending';
    const STATUS_WON = 'won';
    const STATUS_LOST = 'lost';

    // Constantes para tipos de aposta
    const TYPE_SINGLE = 'single';
    const TYPE_SYSTEM = 'system';

    /**
     * Get the group associated with this betting slip
     */
    public function group()
    {
        return $this->belongsTo(Group::class);
    }

    /**
     * Get the draw associated with this betting slip
     */
    public function draw()
    {
        return $this->belongsTo(Draw::class);
    }
    
    /**
     * Get the user associated with this betting slip
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Check if this betting slip is a winner
     */
    public function isWinner()
    {
        return $this->prize_amount > 0;
    }

    /**
     * Get the formatted numbers
     */
    public function getFormattedNumbersAttribute()
    {
        if (!$this->numbers) {
            return null;
        }

        // Formatar números para Totoloto/Euromilhões
        $numbers = implode(', ', $this->numbers);
        $stars = $this->stars ? ' + ' . implode(', ', $this->stars) . ' (Estrelas)' : '';
        return $numbers . $stars;
    }

    /**
     * Calculate the cost per member
     */
    public function getCostPerMemberAttribute()
    {
        $activeMembers = $this->group->activeMembers()->count();
        
        if ($activeMembers === 0) {
            return $this->total_cost;
        }
        
        return round($this->total_cost / $activeMembers, 2);
    }

    /**
     * Calculate the winnings per member
     */
    public function getWinningsPerMemberAttribute()
    {
        $activeMembers = $this->group->activeMembers()->count();
        
        if ($activeMembers === 0 || $this->prize_amount === 0) {
            return 0;
        }
        
        return round($this->prize_amount / $activeMembers, 2);
    }

    /**
     * Validar a aposta antes de salvar
     */
    public function validateBet()
    {
        $errors = [];

        // Verificar saldo do usuário
        if ($this->user->virtual_balance < $this->total_cost) {
            $errors[] = 'Saldo insuficiente para realizar a aposta.';
        }

        // Verificar limite diário
        $dailyTotal = self::where('user_id', $this->user_id)
            ->whereDate('created_at', today())
            ->sum('total_cost');

        if ($dailyTotal + $this->total_cost > $this->group->game->daily_limit) {
            $errors[] = 'Limite diário de apostas excedido.';
        }

        // Validar números baseado no tipo de jogo
        if ($this->group->game->name === 'Totobola') {
            if (!$this->validateTotobolaPredictions()) {
                $errors[] = 'Previsões do Totobola inválidas.';
            }
        } else {
            if (!$this->validateLotteryNumbers()) {
                $errors[] = 'Números da loteria inválidos.';
            }
        }

        // Validar apostas múltiplas
        if ($this->bet_type === self::TYPE_SYSTEM) {
            if (!$this->validateSystemBet()) {
                $errors[] = 'Configuração de aposta múltipla inválida.';
            }
        }

        $this->validation_errors = $errors;
        return empty($errors);
    }

    /**
     * Validar previsões do Totobola
     */
    private function validateTotobolaPredictions()
    {
        if (!is_array($this->predictions) || count($this->predictions) !== 13) {
            return false;
        }

        foreach ($this->predictions as $prediction) {
            if (!in_array($prediction, ['1', 'X', '2'])) {
                return false;
            }
        }

        return true;
    }

    /**
     * Validar números de loteria (Totoloto/Euromilhões)
     */
    private function validateLotteryNumbers()
    {
        if (!is_array($this->numbers)) {
            return false;
        }

        $game = $this->group->game;
        $requiredNumbers = 0;
        $maxNumber = 0;
        $requiredStars = 0;
        $maxStar = 0;

        if ($game->name === 'Euromilhões') {
            $requiredNumbers = 5;
            $maxNumber = 50;
            $requiredStars = 2;
            $maxStar = 12;
        } else { // Totoloto
            $requiredNumbers = 5; // Assumindo 5 números para Totoloto
            $maxNumber = 49;
        }

        // Validar números principais
        if (count($this->numbers) !== $requiredNumbers) {
            return false;
        }
        foreach ($this->numbers as $number) {
            if (!is_numeric($number) || $number < 1 || $number > $maxNumber) {
                return false;
            }
        }

        // Validar estrelas (apenas para Euromilhões)
        if ($game->name === 'Euromilhões') {
            if (!is_array($this->stars) || count($this->stars) !== $requiredStars) {
                return false;
            }
            foreach ($this->stars as $star) {
                if (!is_numeric($star) || $star < 1 || $star > $maxStar) {
                    return false;
                }
            }
        }

        return true;
    }

    /**
     * Validar aposta múltipla (sistema)
     */
    private function validateSystemBet()
    {
        if (!is_array($this->system_details)) {
            return false;
        }

        $type = $this->system_details['type'] ?? null;
        if (!in_array($type, ['full', 'partial'])) {
            return false;
        }

        return true;
    }

    /**
     * Calcular prêmio baseado no tipo de jogo
     */
    public function calculatePrize()
    {
        $game = $this->draw->game;
        $drawResults = $this->draw->results;

        if (!$drawResults) {
            return 0;
        }

        $prize = 0;

        if ($game->name === 'Totobola') {
            $prize = $this->calculateTotobolaPrize();
        } else {
            $prize = $this->calculateLotteryPrize();
        }
        
        $this->prize_amount = $prize;
        $this->save();
        return $prize;
    }

    /**
     * Calcular prêmio para Totobola
     */
    private function calculateTotobolaPrize()
    {
        $correctPredictions = 0;
        $drawResults = $this->draw->results;

        if (!is_array($drawResults) || count($drawResults) !== 13) {
            return 0;
        }

        foreach ($this->predictions as $key => $prediction) {
            if (isset($drawResults[$key]) && $prediction === $drawResults[$key]) {
                $correctPredictions++;
            }
        }

        if ($correctPredictions === 13) {
            return 10000.00;
        } elseif ($correctPredictions === 12) {
            return 1000.00;
        }
        return 0;
    }

    /**
     * Calcular prêmio para Totoloto/Euromilhões
     */
    private function calculateLotteryPrize()
    {
        $game = $this->draw->game;
        $drawResults = $this->draw->results;

        if (!is_array($drawResults) || !isset($drawResults['numbers'])) {
            return 0;
        }

        $correctNumbers = count(array_intersect($this->numbers, $drawResults['numbers']));
        $prize = 0;

        if ($game->name === 'Euromilhões' && isset($drawResults['stars'])) {
            $correctStars = count(array_intersect($this->stars, $drawResults['stars']));
            
            if ($correctNumbers === 5 && $correctStars === 2) {
                $prize = 50000.00;
            } elseif ($correctNumbers === 5 && $correctStars === 1) {
                $prize = 5000.00;
            } elseif ($correctNumbers === 5) {
                $prize = 1000.00;
            }
        } elseif ($game->name === 'Totoloto') {
            if ($correctNumbers === 5) {
                $prize = 20000.00;
            } elseif ($correctNumbers === 4) {
                $prize = 200.00;
            }
        }
        return $prize;
    }

    protected static function booted()
    {
        static::created(function ($bettingSlip) {
            if ($bettingSlip->bet_type === self::TYPE_SINGLE) {
                Activity::logBetPlaced($bettingSlip->user, $bettingSlip->group, $bettingSlip->draw);
            }
        });
    }
}
