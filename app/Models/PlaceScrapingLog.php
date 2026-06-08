<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PlaceScrapingLog extends Model
{
    protected $fillable = [
        'city_id',
        'status',
        'places_found',
        'places_created',
        'places_updated',
        'places_closed',
        'api_requests_made',
        'error_message',
        'started_at',
        'completed_at',
    ];

    protected $casts = [
        'started_at'   => 'datetime',
        'completed_at' => 'datetime',
    ];

    public function city(): BelongsTo
    {
        return $this->belongsTo(City::class);
    }
}
