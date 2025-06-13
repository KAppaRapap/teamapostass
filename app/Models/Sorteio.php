<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sorteio extends Model
{
    use HasFactory;

    protected $fillable = [
        'game_id',
        'date',
        'jackpot_amount',
        'groups_count',
    ];

    public function game()
    {
        return $this->belongsTo(Game::class);
    }
}
