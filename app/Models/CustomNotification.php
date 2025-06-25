<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CustomNotification extends Model
{
    protected $table = 'custom_notifications';
    protected $guarded = [];
    protected $casts = [
        'data' => 'array',
    ];
} 