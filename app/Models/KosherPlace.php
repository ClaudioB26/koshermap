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

    public function reports(): MorphMany
    {
        return $this->morphMany(Report::class, 'reportable');
    }

    public function isApproved(): bool { return $this->status === self::STATUS_APPROVED; }
    public function isRejected(): bool { return $this->status === self::STATUS_REJECTED; }
    public function isPending(): bool  { return $this->status === self::STATUS_PENDING; }
}
