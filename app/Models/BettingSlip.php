<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BettingSlip extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'group_id',
        'user_id',
        'draw_id',
        'numbers',
        'is_system',
        'system_details',
        'total_cost',
        'winnings',
        'prize_amount',
        'is_checked',
        'has_won',
        'is_claimed',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'numbers' => 'array',
        'is_system' => 'boolean',
        'system_details' => 'array',
        'total_cost' => 'decimal:2',
        'winnings' => 'decimal:2',
        'prize_amount' => 'decimal:2',
        'is_checked' => 'boolean',
        'has_won' => 'boolean',
        'is_claimed' => 'boolean',
    ];

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
        // Corrigido: garantir que retorna verdadeiro apenas se has_won for true
        return $this->has_won === true;
    }

    /**
     * Get the formatted numbers
     */
    public function getFormattedNumbersAttribute()
    {
        if (!$this->numbers) {
            return null;
        }

        return implode(', ', $this->numbers);
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
        
        if ($activeMembers === 0 || $this->winnings === 0) {
            return 0;
        }
        
        return round($this->winnings / $activeMembers, 2);
    }
}
