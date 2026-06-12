<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Certifier;
use App\Models\City;
use App\Models\Country;
use App\Models\KosherPlace;
use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SyncController extends Controller
{
    // ── Países ────────────────────────────────────────────────────

    public function countries(Request $request): JsonResponse
    {
        $items  = $request->input('countries', []);
        $synced = 0;

        foreach ($items as $data) {
            if (empty($data['code'])) continue;

            Country::updateOrCreate(
                ['code' => $data['code']],
                [
                    'name'   => $data['name'],
                    'slug'   => $data['slug'],
                    'locale' => $data['locale'] ?? 'es',
                ]
            );
            $synced++;
        }

        return response()->json(['synced' => $synced]);
    }

    // ── Certificadoras ────────────────────────────────────────────

    public function certifiers(Request $request): JsonResponse
    {
        $items  = $request->input('certifiers', []);
        $synced = 0;

        foreach ($items as $data) {
            if (empty($data['slug'])) continue;

            $certifier = Certifier::updateOrCreate(
                ['slug' => $data['slug']],
                [
                    'name'         => $data['name'],
                    'logo_symbol'  => $data['logo_symbol'] ?? null,
                ]
            );

            // Sincronizar países vinculados
            if (!empty($data['country_codes'])) {
                $countryIds = Country::whereIn('code', $data['country_codes'])->pluck('id');
                $certifier->countries()->sync($countryIds);
            }

            $synced++;
        }

        return response()->json(['synced' => $synced]);
    }

    // ── Categorías ────────────────────────────────────────────────

    public function categories(Request $request): JsonResponse
    {
        $items  = $request->input('categories', []);
        $synced = 0;

        // Primero un mapa slug → id para resolver parent_id
        foreach ($items as $data) {
            if (empty($data['slug'])) continue;

            $parentId = null;
            if (!empty($data['parent_slug'])) {
                $parentId = Category::where('slug', $data['parent_slug'])->value('id');
            }

            Category::updateOrCreate(
                ['slug' => $data['slug']],
                [
                    'name'      => $data['name'],
                    'parent_id' => $parentId,
                ]
            );
            $synced++;
        }

        return response()->json(['synced' => $synced]);
    }

    // ── Marcas ────────────────────────────────────────────────────

    public function brands(Request $request): JsonResponse
    {
        $items  = $request->input('brands', []);
        $synced = 0;

        foreach ($items as $data) {
            if (empty($data['slug'])) continue;

            Brand::updateOrCreate(
                ['slug' => $data['slug']],
                ['name' => $data['name']]
            );
            $synced++;
        }

        return response()->json(['synced' => $synced]);
    }

    // ── Productos ─────────────────────────────────────────────────

    public function products(Request $request): JsonResponse
    {
        $items = $request->input('products', []);
        $stats = ['created' => 0, 'updated' => 0, 'skipped' => 0];

        foreach ($items as $data) {
            if (empty($data['slug'])) {
                $stats['skipped']++;
                continue;
            }

            // Resolver IDs por slug/código
            $brandId     = Brand::where('slug', $data['brand_slug'] ?? '')->value('id');
            $certifierId = Certifier::where('slug', $data['certifier_slug'] ?? '')->value('id');
            $categoryId  = Category::where('slug', $data['category_slug'] ?? '')->value('id');

            $payload = [
                'name'         => $data['name'],
                'barcode'      => $data['barcode']    ?? null,
                'image_url'    => $data['image_url']  ?? null,
                'kosher_status'=> $data['kosher_status'],
                'source'       => $data['source']     ?? 'local',
                'description'  => $data['description'] ?? null,
                'brand_id'     => $brandId,
                'certifier_id' => $certifierId,
                'category_id'  => $categoryId,
                'is_active'    => $data['is_active'] ?? true,
            ];

            $existing = Product::where('slug', $data['slug'])->first();

            if ($existing) {
                $existing->update($payload);
                $stats['updated']++;
                $product = $existing;
            } else {
                $product = Product::create(array_merge($payload, ['slug' => $data['slug']]));
                $stats['created']++;
            }

            // Sincronizar países del producto
            if (!empty($data['country_codes'])) {
                $countryIds = Country::whereIn('code', $data['country_codes'])->pluck('id');
                $product->countries()->sync($countryIds);
            }
        }

        return response()->json($stats);
    }

    // ── Ciudades ──────────────────────────────────────────────────

    public function cities(Request $request): JsonResponse
    {
        $cities           = $request->input('cities', []);
        $synced           = 0;
        $missingCountries = [];

        foreach ($cities as $data) {
            $code    = $data['country_code'] ?? '';
            $country = Country::where('code', $code)->first();

            if (!$country) {
                $missingCountries[] = $code;
                continue;
            }

            City::updateOrCreate(
                ['name' => $data['name'], 'country_id' => $country->id],
                [
                    'state'                => $data['state']               ?? null,
                    'latitude'             => $data['latitude'],
                    'longitude'            => $data['longitude'],
                    'search_radius_meters' => $data['search_radius_meters'] ?? 10000,
                    'community_density'    => $data['community_density']    ?? 'medium',
                    'is_active'            => $data['is_active']            ?? true,
                ]
            );
            $synced++;
        }

        return response()->json([
            'synced'           => $synced,
            'missing_countries' => array_unique($missingCountries),
        ]);
    }

    // ── Lugares ───────────────────────────────────────────────────

    public function places(Request $request): JsonResponse
    {
        $places = $request->input('places', []);
        $stats  = ['created' => 0, 'updated' => 0, 'rejected' => 0];

        foreach ($places as $data) {
            $placeId  = $data['google_place_id'] ?? null;
            $citySlug = $data['city_slug']        ?? null;

            if (!$placeId || !$citySlug) {
                $stats['rejected']++;
                continue;
            }

            [$cityName, $countryCode] = explode('__', $citySlug, 2) + ['', ''];
            $city = City::whereHas('country', fn ($q) => $q->where('code', $countryCode))
                ->where('name', $cityName)
                ->first();

            if (!$city) {
                $stats['rejected']++;
                continue;
            }

            $payload = [
                'city_id'               => $city->id,
                'name'                  => $data['name'],
                'status'                => 'approved',
                'place_type'            => $data['place_type']            ?? 'other',
                'address'               => $data['address']               ?? null,
                'latitude'              => $data['latitude']              ?? null,
                'longitude'             => $data['longitude']             ?? null,
                'phone'                 => $data['phone']                 ?? null,
                'website'               => $data['website']               ?? null,
                'google_rating'         => $data['google_rating']         ?? null,
                'google_reviews_count'  => $data['google_reviews_count']  ?? 0,
                'opening_hours'         => $data['opening_hours']         ?? null,
                'google_types'          => $data['google_types']          ?? [],
                'google_photo_ref'      => $data['google_photo_ref']      ?? null,
                'is_permanently_closed' => $data['is_permanently_closed'] ?? false,
                'is_active'             => $data['is_active']             ?? true,
                'last_verified_at'      => $data['last_verified_at']      ?? null,
            ];

            $existing = KosherPlace::where('google_place_id', $placeId)->first();

            if ($existing) {
                if ($existing->isRejected()) { $stats['rejected']++; continue; }
                $existing->update($payload);
                $stats['updated']++;
            } else {
                KosherPlace::create(array_merge($payload, ['google_place_id' => $placeId]));
                $stats['created']++;
            }
        }

        return response()->json($stats);
    }
}
