<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Group extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'description',
        'game_id',
        'city',
        'region',
        'admin_id',
        'is_public',
        'max_members',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'is_public' => 'boolean',
        'max_members' => 'integer',
    ];

    /**
     * Get the game associated with this group
     */
    public function game()
    {
        return $this->belongsTo(Game::class);
    }

    /**
     * Get the admin of this group
     */
    public function admin()
    {
        return $this->belongsTo(User::class, 'admin_id');
    }

    /**
     * Get the members of this group
     */
    public function members()
    {
        return $this->belongsToMany(User::class)
            ->withPivot('status')
            ->withTimestamps();
    }

    /**
     * Get active members of this group
     */
    public function activeMembers()
    {
        return $this->belongsToMany(User::class)
            ->wherePivot('status', 'active')
            ->withTimestamps();
    }

    /**
     * Get the betting slips for this group
     */
    public function bettingSlips()
    {
        return $this->hasMany(BettingSlip::class);
    }

    /**
     * Check if the group has reached its maximum member limit
     */
    public function hasReachedMemberLimit()
    {
        if ($this->max_members === 0) {
            return false; // No limit
        }

        return $this->activeMembers()->count() >= $this->max_members;
    }
}
