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
     * Check if a user is a member of this group.
     *
     * @param  \App\Models\User  $user
     * @return bool
     */
    public function isMember(User $user)
    {
        return $this->members()->where('user_id', $user->id)->exists();
    }

    /**
     * Check if a user is the admin of this group.
     *
     * @param  \App\Models\User  $user
     * @return bool
     */
    public function isAdmin(User $user)
    {
        return $this->admin_id === $user->id;
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

    public function activities()
    {
        return $this->hasMany(Activity::class);
    }

    public function recentActivities($limit = 5)
    {
        return $this->activities()
            ->orderBy('created_at', 'desc')
            ->take($limit)
            ->get();
    }

    public function addMember(User $user)
    {
        if (!$this->hasReachedMemberLimit()) {
            $this->members()->attach($user->id);
            Activity::logGroupJoined($user, $this);
            return true;
        }
        return false;
    }

    public function removeMember(User $user)
    {
        $this->members()->detach($user->id);
        Activity::logGroupLeft($user, $this);
        return true;
    }

    protected static function booted()
    {
        static::created(function ($group) {
            Activity::logGroupCreated($group->admin, $group);
        });
    }
}
