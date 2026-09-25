<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Banner extends Model
{
    const SLOT_TOP    = 'top';
    const SLOT_BOTTOM = 'bottom';

    const SLOTS = [
        self::SLOT_TOP    => 'Arriba (debajo del menú)',
        self::SLOT_BOTTOM => 'Abajo (antes del pie de página)',
    ];

    // "all" = artículos + certificadoras. Los banners no se muestran en
    // paginas de cuenta, formularios ni paginas legales.
    const PAGES = [
        'all'         => 'Artículos y certificadoras',
        'articles'    => 'Solo artículos',
        'certifiers'  => 'Solo certificadoras',
    ];

    protected $fillable = [
        'name', 'advertiser', 'slot', 'pages', 'image_path', 'width', 'height',
        'target_url', 'alt', 'starts_on', 'ends_on', 'is_active', 'notes',
    ];

    protected $casts = [
        'starts_on'   => 'date',
        'ends_on'     => 'date',
        'is_active'   => 'boolean',
        'impressions' => 'integer',
        'clicks'      => 'integer',
    ];

    /** Activos, y dentro de su ventana de fechas (si tienen). */
    public function scopeLive($query)
    {
        $today = now()->toDateString();

        return $query->where('is_active', true)
            ->where(fn ($q) => $q->whereNull('starts_on')->orWhere('starts_on', '<=', $today))
            ->where(fn ($q) => $q->whereNull('ends_on')->orWhere('ends_on', '>=', $today));
    }

    /** active | paused | scheduled | expired */
    public function getStatusAttribute(): string
    {
        if (! $this->is_active) {
            return 'paused';
        }
        if ($this->starts_on && $this->starts_on->isFuture()) {
            return 'scheduled';
        }
        if ($this->ends_on && $this->ends_on->copy()->endOfDay()->isPast()) {
            return 'expired';
        }

        return 'active';
    }

    /** Porcentaje de clics sobre impresiones (null si todavia no hubo impresiones). */
    public function getCtrAttribute(): ?float
    {
        return $this->impressions > 0 ? round($this->clicks / $this->impressions * 100, 2) : null;
    }
}
