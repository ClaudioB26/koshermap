<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class Review extends Model
{
    const STATUS_PENDING  = 'pending';
    const STATUS_APPROVED = 'approved';
    const STATUS_REJECTED = 'rejected';

    /** Cuántos rechazos de una misma IP activan el bloqueo */
    const BLOCK_THRESHOLD = 2;

    /** Minutos de espera tras aprobación antes de mostrarse públicamente */
    const VISIBILITY_DELAY_MINUTES = 5;

    protected $fillable = [
        'product_id',
        'user_id',
        'author_name',
        'content',
        'rating',
        'is_approved',   // columna legacy, se mantiene por compatibilidad
        'status',
        'flagged',
        'ip_address',
        'approved_at',
        'rejection_note',
    ];

    protected $casts = [
        'approved_at' => 'datetime',
        'is_approved' => 'boolean',
        'flagged'     => 'boolean',
    ];

    // ── Relaciones ──────────────────────────────────────────────

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // ── Scopes ──────────────────────────────────────────────────

    /**
     * Reviews públicamente visibles:
     * - Pendientes SIN FLAG que ya pasaron 5 min (auto-publicadas)
     * - O explícitamente aprobadas por el moderador (con o sin flag)
     * Flagged=true + pending nunca se auto-publica; el moderador decide.
     */
    public function scopeVisible(Builder $query): Builder
    {
        return $query->where(function ($q) {
            // Auto-publicadas: pendiente, sin flag, pasaron los 5 min
            $q->where('status', self::STATUS_PENDING)
              ->where('flagged', false)
              ->where('created_at', '<=', now()->subMinutes(self::VISIBILITY_DELAY_MINUTES));
        })->orWhere('status', self::STATUS_APPROVED);
    }

    public function scopePending(Builder $query): Builder
    {
        return $query->where('status', self::STATUS_PENDING);
    }

    public function scopeApproved(Builder $query): Builder
    {
        return $query->where('status', self::STATUS_APPROVED);
    }

    public function scopeRejected(Builder $query): Builder
    {
        return $query->where('status', self::STATUS_REJECTED);
    }

    // ── Helpers ─────────────────────────────────────────────────

    /**
     * IP bloqueada si tiene 2+ envíos con palabras feas (flagged=true).
     * Se cuenta aunque el moderador todavía no los haya revisado.
     */
    public static function isIpBlocked(string $ip): bool
    {
        return static::where('ip_address', $ip)
            ->where('flagged', true)
            ->count() >= self::BLOCK_THRESHOLD;
    }
}
