<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Certifier extends Model
{
    const STATUS_PENDING  = 'pending';
    const STATUS_APPROVED = 'approved';
    const STATUS_REJECTED = 'rejected';

    const TIER_FREE      = 'free';
    const TIER_DESTACADA = 'destacada';
    const TIER_PRO       = 'pro';

    // Orden de aparicion en /certifiers: pro primero, despues destacada, gratis al final.
    const TIER_ORDER = [self::TIER_PRO => 0, self::TIER_DESTACADA => 1, self::TIER_FREE => 2];

    protected $fillable = [
        'name', 'slug', 'logo_symbol', 'about', 'website', 'contact_email', 'phone', 'hours', 'address',
        'status', 'tier', 'rejection_reason', 'owner_id',
        'rabbi_name', 'founded_year', 'coverage_description', 'reference_info', 'documents',
        'submitted_by_name', 'submitted_by_email', 'submitted_by_phone',
    ];

    protected $casts = [
        'documents' => 'array',
    ];

    public function products()
    {
        return $this->hasMany(Product::class);
    }

    public function leads()
    {
        return $this->hasMany(CertifierLead::class);
    }

    /**
     * Relación: Una certificadora tiene cobertura en varios Países.
     */
    public function countries()
    {
        return $this->belongsToMany(Country::class, 'certifier_country');
    }

    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    public function isApproved(): bool
    {
        return $this->status === self::STATUS_APPROVED;
    }

    public function isPending(): bool
    {
        return $this->status === self::STATUS_PENDING;
    }

    public function isRejected(): bool
    {
        return $this->status === self::STATUS_REJECTED;
    }

    public function scopeApproved($query)
    {
        return $query->where('status', self::STATUS_APPROVED);
    }
}
