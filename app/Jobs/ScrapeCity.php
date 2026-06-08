<?php

namespace App\Jobs;

use App\Models\City;
use App\Models\KosherPlace;
use App\Models\PlaceScrapingLog;
use App\Services\GoogleMapsScraperService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class ScrapeCity implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $timeout = 1200;
    public int $tries   = 2;

    public function __construct(
        private readonly City $city,
        private readonly bool $onlyFirstTerm = false,
    ) {}

    public function handle(GoogleMapsScraperService $scraper): void
    {
        $log = PlaceScrapingLog::create([
            'city_id'    => $this->city->id,
            'status'     => 'running',
            'started_at' => now(),
        ]);

        try {
            $locale      = $this->city->country->locale ?? 'es';
            $countryName = $this->city->country->name ?? '';

            $places = $scraper->searchKosherPlaces(
                lat:           $this->city->latitude,
                lng:           $this->city->longitude,
                radiusMeters:  $this->city->search_radius_meters,
                cityName:      $this->city->name,
                countryName:   $countryName,
                locale:        $locale,
                onlyFirstTerm: $this->onlyFirstTerm,
            );

            $stats         = ['found' => 0, 'created' => 0, 'updated' => 0, 'closed' => 0];
            $foundPlaceIds = [];

            foreach ($places as $place) {
                $placeId = $place['google_place_id'] ?? null;
                if (!$placeId || empty($place['name'])) {
                    continue;
                }

                $foundPlaceIds[] = $placeId;
                $stats['found']++;

                $data = [
                    'city_id'               => $this->city->id,
                    'name'                  => $place['name'],
                    'place_type'            => $place['place_type']           ?? 'other',
                    'address'               => $place['address']              ?? null,
                    'latitude'              => $place['latitude']              ?? null,
                    'longitude'             => $place['longitude']             ?? null,
                    'phone'                 => $place['phone']                 ?? null,
                    'website'               => $place['website']               ?? null,
                    'google_rating'         => $place['google_rating']         ?? null,
                    'google_reviews_count'  => $place['google_reviews_count']  ?? 0,
                    'opening_hours'         => $place['opening_hours']         ?? null,
                    'google_types'          => $place['google_types']          ?? [],
                    'google_photo_ref'      => $place['google_photo_ref']      ?? null,
                    'is_permanently_closed' => $place['is_permanently_closed'] ?? false,
                    'is_active'             => !($place['is_permanently_closed'] ?? false),
                    'last_verified_at'      => now(),
                ];

                $existing = KosherPlace::where('google_place_id', $placeId)->first();

                if ($existing) {
                    // Preservar el status de moderación — no revertir rejected/approved a pending
                    $existing->update($data);
                    $stats['updated']++;
                } else {
                    KosherPlace::create(array_merge($data, [
                        'google_place_id' => $placeId,
                        'status'          => \App\Models\KosherPlace::STATUS_PENDING,
                    ]));
                    $stats['created']++;
                }
            }

            $stats['closed'] = KosherPlace::where('city_id', $this->city->id)
                ->where('is_permanently_closed', false)
                ->whereNotIn('google_place_id', $foundPlaceIds)
                ->whereNotNull('last_verified_at')
                ->where('last_verified_at', '<', now()->subDays(30))
                ->update(['is_active' => false]);

            $this->city->markScraped();

            $log->update([
                'status'            => 'completed',
                'places_found'      => $stats['found'],
                'places_created'    => $stats['created'],
                'places_updated'    => $stats['updated'],
                'places_closed'     => $stats['closed'],
                'api_requests_made' => $scraper->getPageCount(),
                'completed_at'      => now(),
            ]);

            Log::info("ScrapeCity completado: {$this->city->name}", $stats);

        } catch (\Throwable $e) {
            $log->update([
                'status'        => 'failed',
                'error_message' => $e->getMessage(),
                'completed_at'  => now(),
            ]);
            Log::error("ScrapeCity falló: {$this->city->name}", ['error' => $e->getMessage()]);
            throw $e;
        }
    }
}
