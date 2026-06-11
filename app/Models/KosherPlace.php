<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class KosherPlace extends Model
{
    const STATUS_PENDING  = 'pending';
    const STATUS_APPROVED = 'approved';
    const STATUS_REJECTED = 'rejected';

    protected $fillable = [
        'city_id',
        'google_place_id',
        'status',
        'rejection_reason',
        'name',
        'place_type',
        'address',
        'latitude',
        'longitude',
        'phone',
        'website',
        'google_rating',
        'google_reviews_count',
        'opening_hours',
        'google_types',
        'google_photo_ref',
        'is_permanently_closed',
        'is_active',
        'last_verified_at',
        'certifier_id',
        'certifier_other',
        'owner_name',
        'owner_email',
        'owner_phone',
        'source',
    ];

    /**
     * Tipos de lugar que requieren certificación kosher.
     */
    public const CERTIFIABLE_TYPES = [
        'restaurant',
        'bakery',
        'bar',
        'confectionery',
        'ice_cream',
        'takeaway',
    ];

    protected $casts = [
        'opening_hours'         => 'array',
        'google_types'          => 'array',
        'is_permanently_closed' => 'boolean',
        'is_active'             => 'boolean',
        'last_verified_at'      => 'datetime',
        'synced_at'             => 'datetime',
    ];

    public function city(): BelongsTo
    {
        return $this->belongsTo(City::class);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true)->where('is_permanently_closed', false);
    }

    public function scopeApproved($query)
    {
        return $query->where('status', self::STATUS_APPROVED);
    }

    // Aprobados que nunca se sincronizaron O que cambiaron después del último sync
    public function scopePendingSync($query)
    {
        return $query->approved()
            ->where(function ($q) {
                $q->whereNull('synced_at')
                  ->orWhereColumn('updated_at', '>', 'synced_at');
            });
    }

    public function scopePending($query)
    {
        return $query->where('status', self::STATUS_PENDING);
    }

    public function scopeOfType($query, string $type)
    {
        return $query->where('place_type', $type);
    }

    /**
     * Tipos de lugar disponibles: emoji, etiqueta y color para badges.
     */
    public static function types(): array
    {
        return [
            'restaurant'    => ['emoji' => '🍽️', 'label' => 'Restaurantes',   'badge' => 'bg-orange-100 text-orange-700'],
            'bakery'        => ['emoji' => '🥐', 'label' => 'Panaderías',     'badge' => 'bg-yellow-100 text-yellow-700'],
            'bar'           => ['emoji' => '🍷', 'label' => 'Bares',          'badge' => 'bg-purple-100 text-purple-700'],
            'confectionery' => ['emoji' => '☕', 'label' => 'Cafeterías',     'badge' => 'bg-pink-100 text-pink-700'],
            'ice_cream'     => ['emoji' => '🍦', 'label' => 'Heladerías',     'badge' => 'bg-cyan-100 text-cyan-700'],
            'supermarket'   => ['emoji' => '🛒', 'label' => 'Supermercados',  'badge' => 'bg-lime-100 text-lime-700'],
            'temple'        => ['emoji' => '🕍', 'label' => 'Sinagogas',      'badge' => 'bg-blue-100 text-blue-700'],
            'school'        => ['emoji' => '🏫', 'label' => 'Escuelas',       'badge' => 'bg-green-100 text-green-700'],
            'cemetery'      => ['emoji' => '🪦', 'label' => 'Cementerios',    'badge' => 'bg-stone-100 text-stone-700'],
            'community'     => ['emoji' => '🏛️', 'label' => 'Comunidades',    'badge' => 'bg-indigo-100 text-indigo-700'],
            'takeaway'      => ['emoji' => '🥡', 'label' => 'Take Away',      'badge' => 'bg-amber-100 text-amber-700'],
            'other'         => ['emoji' => '📍', 'label' => 'Otros',          'badge' => 'bg-gray-100 text-gray-600'],
        ];
    }

    public function reports(): MorphMany
    {
        return $this->morphMany(Report::class, 'reportable');
    }

    public function certifier(): BelongsTo
    {
        return $this->belongsTo(Certifier::class);
    }

    public function isApproved(): bool { return $this->status === self::STATUS_APPROVED; }
    public function isRejected(): bool { return $this->status === self::STATUS_REJECTED; }
    public function isPending(): bool  { return $this->status === self::STATUS_PENDING; }
}
