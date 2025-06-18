<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Activity extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'group_id',
        'type',
        'description',
        'data'
    ];

    protected $casts = [
        'data' => 'array'
    ];

    // Tipos de atividade
    const TYPE_BET_PLACED = 'bet_placed';
    const TYPE_BET_WON = 'bet_won';
    const TYPE_BET_LOST = 'bet_lost';
    const TYPE_GROUP_JOINED = 'group_joined';
    const TYPE_GROUP_LEFT = 'group_left';
    const TYPE_GROUP_CREATED = 'group_created';
    const TYPE_DRAW_COMPLETED = 'draw_completed';
    const TYPE_BALANCE_UPDATED = 'balance_updated';

    // Relacionamentos
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function group()
    {
        return $this->belongsTo(Group::class);
    }

    // Métodos auxiliares
    public static function logBetPlaced($user, $bettingSlip)
    {
        return self::create([
            'user_id' => $user->id,
            'group_id' => $bettingSlip->group_id,
            'type' => self::TYPE_BET_PLACED,
            'description' => "Aposta realizada no jogo {$bettingSlip->draw->game->name}",
            'data' => [
                'betting_slip_id' => $bettingSlip->id,
                'game_id' => $bettingSlip->draw->game_id,
                'draw_id' => $bettingSlip->draw_id,
                'amount' => $bettingSlip->total_cost
            ]
        ]);
    }

    public static function logBetWon($user, $bettingSlip)
    {
        return self::create([
            'user_id' => $user->id,
            'group_id' => $bettingSlip->group_id,
            'type' => self::TYPE_BET_WON,
            'description' => "Ganhou €{$bettingSlip->prize_amount} no jogo {$bettingSlip->draw->game->name}",
            'data' => [
                'betting_slip_id' => $bettingSlip->id,
                'game_id' => $bettingSlip->draw->game_id,
                'draw_id' => $bettingSlip->draw_id,
                'prize_amount' => $bettingSlip->prize_amount
            ]
        ]);
    }

    public static function logBetLost($user, $bettingSlip)
    {
        return self::create([
            'user_id' => $user->id,
            'group_id' => $bettingSlip->group_id,
            'type' => self::TYPE_BET_LOST,
            'description' => "Aposta perdida no jogo {$bettingSlip->draw->game->name}",
            'data' => [
                'betting_slip_id' => $bettingSlip->id,
                'game_id' => $bettingSlip->draw->game_id,
                'draw_id' => $bettingSlip->draw_id
            ]
        ]);
    }

    public static function logGroupJoined($user, $group)
    {
        return self::create([
            'user_id' => $user->id,
            'group_id' => $group->id,
            'type' => self::TYPE_GROUP_JOINED,
            'description' => "Entrou no grupo {$group->name}",
            'data' => [
                'group_id' => $group->id,
                'game_id' => $group->game_id
            ]
        ]);
    }

    public static function logGroupLeft($user, $group)
    {
        return self::create([
            'user_id' => $user->id,
            'group_id' => $group->id,
            'type' => self::TYPE_GROUP_LEFT,
            'description' => "Saiu do grupo {$group->name}",
            'data' => [
                'group_id' => $group->id,
                'game_id' => $group->game_id
            ]
        ]);
    }

    public static function logGroupCreated($user, $group)
    {
        return self::create([
            'user_id' => $user->id,
            'group_id' => $group->id,
            'type' => self::TYPE_GROUP_CREATED,
            'description' => "Criou o grupo {$group->name}",
            'data' => [
                'group_id' => $group->id,
                'game_id' => $group->game_id
            ]
        ]);
    }

    public static function logDrawCompleted($draw)
    {
        return self::create([
            'type' => self::TYPE_DRAW_COMPLETED,
            'description' => "Sorteio {$draw->game->name} realizado",
            'data' => [
                'game_id' => $draw->game_id,
                'draw_id' => $draw->id,
                'jackpot' => $draw->jackpot
            ]
        ]);
    }

    public static function logBalanceUpdated($user, $amount, $type)
    {
        return self::create([
            'user_id' => $user->id,
            'type' => self::TYPE_BALANCE_UPDATED,
            'description' => "Saldo {$type} de €{$amount}",
            'data' => [
                'amount' => $amount,
                'type' => $type
            ]
        ]);
    }
} 