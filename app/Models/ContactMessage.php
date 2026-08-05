<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ContactMessage extends Model
{
    protected $fillable = [
        'certifier_id', 'name', 'email', 'message', 'accepted_privacy', 'status',
    ];

    protected $casts = [
        'accepted_privacy' => 'boolean',
    ];

    public function certifier()
    {
        return $this->belongsTo(Certifier::class);
    }
}
