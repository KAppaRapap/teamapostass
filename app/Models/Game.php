<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Game extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'type',
        'description',
        'rules',
        'price_per_bet',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'price_per_bet' => 'decimal:2',
    ];

    /**
     * Get the groups associated with this game
     */
    public function groups()
    {
        return $this->hasMany(Group::class);
    }

    /**
     * Get the draws for this game
     */
    public function draws()
    {
        return $this->hasMany(Draw::class);
    }

    /**
     * Get the next draw for this game
     */
    public function nextDraw()
    {
        return $this->draws()
            ->where('draw_date', '>', now())
            ->where('is_completed', false)
            ->orderBy('draw_date', 'asc')
            ->first();
    }

    /**
     * Get the latest completed draw for this game
     */
    public function latestCompletedDraw()
    {
        return $this->draws()
            ->where('is_completed', true)
            ->orderBy('draw_date', 'desc')
            ->first();
    }
}
