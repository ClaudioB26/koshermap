<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Report extends Model
{
    protected $fillable = [
        'email', 'reason', 'observation',
        'status', 'admin_notes', 'reviewed_at',
    ];

    protected $casts = [
        'reviewed_at' => 'datetime',
    ];

    // Claves de motivos — los labels se traducen con __() en las vistas
    const REASON_KEYS_PRODUCT = [
        'not_kosher', 'wrong_label', 'lost_certification', 'wrong_certifier', 'other',
    ];

    const REASON_KEYS_PLACE = [
        'lost_certification', 'not_observant', 'closed', 'wrong_type', 'open_shabbat', 'other',
    ];

    public static function reasonsProduct(): array
    {
        return array_combine(
            self::REASON_KEYS_PRODUCT,
            array_map(fn ($k) => __("report.product.$k"), self::REASON_KEYS_PRODUCT)
        );
    }

    public static function reasonsPlace(): array
    {
        return array_combine(
            self::REASON_KEYS_PLACE,
            array_map(fn ($k) => __("report.place.$k"), self::REASON_KEYS_PLACE)
        );
    }

    public function reasonLabel(): string
    {
        $key = 'report.product.' . $this->reason;
        $t   = __($key);
        if ($t !== $key) return $t;

        $key2 = 'report.place.' . $this->reason;
        $t2   = __($key2);
        return $t2 !== $key2 ? $t2 : $this->reason;
    }

    public function reportable(): MorphTo
    {
        return $this->morphTo();
    }

    public function isPending(): bool
    {
        return $this->status === 'pending';
    }
}
