<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Pedido de un banner autoservicio: el anunciante elige ubicación y duración,
 * paga (Mercado Pago o transferencia) y el banner sale recién cuando el pago
 * está aprobado Y el admin revisó la imagen.
 */
class BannerOrder extends Model
{
    const PAY_MP       = 'mercadopago';
    const PAY_TRANSFER = 'transfer';

    const PENDING  = 'pending';
    const APPROVED = 'approved';
    const REJECTED = 'rejected';

    protected $fillable = [
        'user_id', 'banner_id', 'slot', 'months', 'amount', 'currency', 'payment_method',
        'payment_status', 'review_status', 'mp_payment_id', 'transfer_proof_path',
        'rejection_reason', 'activated_at',
    ];

    protected $casts = ['activated_at' => 'datetime', 'amount' => 'float'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function banner(): BelongsTo
    {
        return $this->belongsTo(Banner::class);
    }

    /**
     * awaiting_payment | awaiting_proof | verifying_transfer | in_review |
     * active | expired | rejected_payment | rejected_review
     */
    public function getStateAttribute(): string
    {
        if ($this->payment_status === self::REJECTED) {
            return 'rejected_payment';
        }
        if ($this->review_status === self::REJECTED) {
            return 'rejected_review';
        }
        if ($this->payment_status === self::PENDING) {
            if ($this->payment_method === self::PAY_TRANSFER) {
                return $this->transfer_proof_path ? 'verifying_transfer' : 'awaiting_proof';
            }

            return 'awaiting_payment';
        }
        if ($this->review_status === self::PENDING) {
            return 'in_review';
        }

        return $this->banner && $this->banner->status === 'expired' ? 'expired' : 'active';
    }

    /** Hay algo que el admin tiene que resolver. */
    public function scopeNeedsAdmin($query)
    {
        return $query->where(function ($q) {
            $q->where(fn ($t) => $t->where('payment_method', self::PAY_TRANSFER)
                    ->where('payment_status', self::PENDING)->whereNotNull('transfer_proof_path'))
              ->orWhere(fn ($r) => $r->where('payment_status', self::APPROVED)->where('review_status', self::PENDING));
        });
    }
}
