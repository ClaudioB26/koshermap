<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ContactMessage extends Model
{
    protected $fillable = [
        'name', 'email', 'message', 'accepted_privacy', 'status',
    ];

    protected $casts = [
        'accepted_privacy' => 'boolean',
    ];
}
