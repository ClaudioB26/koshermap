<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Suscriptor del newsletter. Solo los "confirmed" (doble confirmación) reciben
 * campañas; el consentimiento queda registrado (fecha, IP, idioma, origen).
 */
class NewsletterSubscriber extends Model
{
    const PENDING      = 'pending';
    const CONFIRMED    = 'confirmed';
    const UNSUBSCRIBED = 'unsubscribed';

    protected $fillable = [
        'email', 'locale', 'status', 'token', 'consent_ip', 'consented_at',
        'confirmed_at', 'unsubscribed_at', 'source',
    ];

    protected $casts = [
        'consented_at'    => 'datetime',
        'confirmed_at'    => 'datetime',
        'unsubscribed_at' => 'datetime',
    ];
}
