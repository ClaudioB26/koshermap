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

    public function markScraped(): void
    {
        $interval = self::DENSITY_INTERVALS[$this->community_density] ?? 180;

        $this->update([
            'last_scraped_at'     => now(),
            'next_scrape_at'      => now()->addDays($interval),
            'scrape_interval_days'=> $interval,
        ]);
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
