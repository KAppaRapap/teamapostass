<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'is_admin',
        'is_banned',
        'profile_photo',
        'virtual_balance',
        'language',
        'timezone',
        'currency',
        'theme',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'is_admin' => 'boolean',
        'is_banned' => 'boolean',
    ];

    /**
     * Get the groups that the user belongs to
     */
    public function groups()
    {
        return $this->belongsToMany(Group::class)
            ->withPivot('status')
            ->withTimestamps();
    }

    /**
     * Get the groups that the user administers
     */
    public function administeredGroups()
    {
        return $this->hasMany(Group::class, 'admin_id');
    }

    /**
     * Get the user's notifications
     */
    public function userNotifications()
    {
        return $this->hasMany(Notification::class);
    }

    /**
     * Check if user is banned
     */
    public function isBanned()
    {
        return $this->is_banned;
    }
    
    /**
     * Get the betting slips that belong to the user
     */
    public function bettingSlips()
    {
        return $this->hasMany(BettingSlip::class);
    }

    /**
     * Get the URL for the user's profile photo.
     *
     * @return string
     */
    public function getProfilePhotoUrlAttribute()
    {
        if ($this->profile_photo) {
            return asset('storage/' . $this->profile_photo);
        }

        // Gerar avatar automático com a primeira letra do nome
        return $this->generateAvatarUrl();
    }

    /**
     * Generate an automatic avatar URL with the user's initial
     *
     * @return string
     */
    public function generateAvatarUrl()
    {
        // Usar o sistema local de avatares SVG
        return route('avatar.generate', [
            'userId' => $this->id,
            'name' => urlencode($this->name)
        ]);
    }

    /**
     * Get the activities related to the user
     */
    public function activities()
    {
        return $this->hasMany(Activity::class);
    }
}
