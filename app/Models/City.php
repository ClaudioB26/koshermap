<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class City extends Model
{
    protected $fillable = [
        'country_id',
        'name',
        'state',
        'latitude',
        'longitude',
        'search_radius_meters',
        'community_density',
        'scrape_interval_days',
        'last_scraped_at',
        'next_scrape_at',
        'is_active',
    ];

    protected $casts = [
        'last_scraped_at' => 'datetime',
        'next_scrape_at'  => 'datetime',
        'is_active'       => 'boolean',
    ];

    public const DENSITY_INTERVALS = [
        'major'  => 60,
        'large'  => 90,
        'medium' => 180,
        'small'  => 270,
        'tiny'   => 365,
    ];

    // Cantidad mínima de lugares encontrados para asignar cada densidad.
    // Se recorre en orden: la primera cuyo umbral se alcance, gana.
    public const PLACE_COUNT_DENSITY_THRESHOLDS = [
        'major'  => 150,
        'large'  => 80,
        'medium' => 40,
        'small'  => 15,
        'tiny'   => 0,
    ];

    public function country(): BelongsTo
    {
        return $this->belongsTo(Country::class);
    }

    public function kosherPlaces(): HasMany
    {
        return $this->hasMany(KosherPlace::class);
    }

    public function scrapingLogs(): HasMany
    {
        return $this->hasMany(PlaceScrapingLog::class);
    }

    public function needsScraping(): bool
    {
        return is_null($this->next_scrape_at) || $this->next_scrape_at->isPast();
    }

    /**
     * Registra que la ciudad fue scrapeada y calcula cuándo le toca de nuevo.
     *
     * Si se pasa la cantidad de lugares encontrados, la densidad de la
     * comunidad (y por lo tanto la frecuencia de refresco) se recalcula
     * automáticamente en base a ese resultado.
     */
    public function markScraped(?int $placesFound = null): void
    {
        $density = $placesFound !== null
            ? self::densityForPlaceCount($placesFound)
            : ($this->community_density ?? 'medium');

        $interval = self::DENSITY_INTERVALS[$density] ?? 180;

        $this->update([
            'community_density'    => $density,
            'last_scraped_at'      => now(),
            'next_scrape_at'       => now()->addDays($interval),
            'scrape_interval_days' => $interval,
        ]);
    }

    /**
     * Determina la densidad de comunidad según la cantidad de lugares encontrados.
     */
    public static function densityForPlaceCount(int $placesFound): string
    {
        foreach (self::PLACE_COUNT_DENSITY_THRESHOLDS as $density => $threshold) {
            if ($placesFound >= $threshold) {
                return $density;
            }
        }

        return 'tiny';
    }

    public function scopeDueForScraping($query)
    {
        return $query->where('is_active', true)
                     ->where(function ($q) {
                         $q->whereNull('next_scrape_at')
                           ->orWhere('next_scrape_at', '<=', now());
                     });
    }
}
