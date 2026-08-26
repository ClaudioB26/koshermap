<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CertifierLead extends Model
{
    protected $fillable = [
        'certifier_id', 'name', 'company', 'email', 'phone', 'product_type', 'message',
    ];

    public function certifier(): BelongsTo
    {
        return $this->belongsTo(Certifier::class);
    }
}
