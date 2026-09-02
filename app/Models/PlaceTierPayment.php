<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PlaceTierPayment extends Model
{
    const STATUS_PENDING  = 'pending';
    const STATUS_APPROVED = 'approved';
    const STATUS_REJECTED = 'rejected';

    const METHOD_MERCADOPAGO = 'mercadopago';
    const METHOD_TRANSFER    = 'transfer';

    protected $fillable = [
        'place_id', 'tier', 'months', 'amount', 'currency', 'payment_method',
        'status', 'mp_payment_id', 'transfer_proof_path', 'rejection_reason',
    ];

    public function place(): BelongsTo
    {
        return $this->belongsTo(KosherPlace::class, 'place_id');
    }
}
