<?php

namespace App\Http\Controllers;

use App\Models\Certifier;
use App\Models\Country;
use App\Models\KosherPlace;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PlaceSubmissionController extends Controller
{
    public function create()
    {
        $countries  = Country::with(['cities' => fn ($q) => $q->orderBy('name')])->orderBy('name')->get();
        $certifiers = Certifier::orderBy('name')->get();
        $types      = KosherPlace::types();

        return view('places.submit', compact('countries', 'certifiers', 'types'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'            => 'required|string|max:255',
            'place_type'      => 'required|in:' . implode(',', array_keys(KosherPlace::types())),
            'orientation'     => 'nullable|in:' . implode(',', array_keys(KosherPlace::orientations())),
            'city_id'         => 'required|exists:cities,id',
            'address'         => 'nullable|string|max:255',
            'phone'           => 'nullable|string|max:50',
            'website'         => 'nullable|url|max:255',
            'certifier_id'    => 'nullable|exists:certifiers,id',
            'certifier_other' => 'nullable|string|max:255',
            'owner_phone'     => 'nullable|string|max:50',
            'terms'           => 'accepted',
        ]);

        if (!in_array($validated['place_type'], KosherPlace::CERTIFIABLE_TYPES, true)) {
            $validated['certifier_id']    = null;
            $validated['certifier_other'] = null;
        }

        $validated['orientation'] = in_array($validated['place_type'], KosherPlace::ORIENTABLE_TYPES, true)
            ? ($validated['orientation'] ?? 'orthodox')
            : 'orthodox';

        $user = $request->user();

        KosherPlace::create([
            'google_place_id' => 'manual-' . Str::uuid(),
            'source'          => 'owner',
            'status'          => KosherPlace::STATUS_PENDING,
            'owner_id'        => $user->id,
            'name'            => $validated['name'],
            'place_type'      => $validated['place_type'],
            'orientation'     => $validated['orientation'],
            'city_id'         => $validated['city_id'],
            'address'         => $validated['address'] ?? null,
            'phone'           => $validated['phone'] ?? null,
            'website'         => $validated['website'] ?? null,
            'certifier_id'    => $validated['certifier_id'] ?? null,
            'certifier_other' => $validated['certifier_other'] ?? null,
            'owner_name'      => $user->name,
            'owner_email'     => $user->email,
            'owner_phone'     => $validated['owner_phone'] ?? null,
            'is_active'       => true,
        ]);

        return redirect()->route('account.places')
            ->with('success', '¡Gracias! Tu local fue enviado y será revisado por nuestro equipo antes de publicarse.');
    }
}
