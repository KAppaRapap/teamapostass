<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FootballMatch extends Model
{
    use HasFactory;

    protected $fillable = [
        'home_team',
        'away_team',
        'match_date',
        'league',
        'result',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'match_date' => 'datetime',
    ];

    /**
     * The draws that include this football match.
     */
    public function draws()
    {
        return $this->belongsToMany(Draw::class, 'draw_football_match')->withPivot('match_number');
    }
}
