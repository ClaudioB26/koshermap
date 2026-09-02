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
        $validated = $this->validatePlace($request);
        $user = $request->user();

        KosherPlace::create(array_merge($validated, [
            'google_place_id' => 'manual-' . Str::uuid(),
            'source'          => 'owner',
            'status'          => KosherPlace::STATUS_PENDING,
            'owner_id'        => $user->id,
            'owner_name'      => $user->name,
            'owner_email'     => $user->email,
            'is_active'       => true,
        ]));

        return redirect()->route('account.places')
            ->with('success', '¡Gracias! Tu local fue enviado y será revisado por nuestro equipo antes de publicarse.');
    }

    public function edit(Request $request, KosherPlace $place)
    {
        abort_unless($place->owner_id === $request->user()->id, 403);

        $countries  = Country::with(['cities' => fn ($q) => $q->orderBy('name')])->orderBy('name')->get();
        $certifiers = Certifier::orderBy('name')->get();
        $types      = KosherPlace::types();

        return view('places.edit', compact('place', 'countries', 'certifiers', 'types'));
    }

    public function update(Request $request, KosherPlace $place)
    {
        abort_unless($place->owner_id === $request->user()->id, 403);

        $validated = $this->validatePlace($request);

        // Si ya estaba publicado o rechazado, editar lo vuelve a mandar a revision:
        // los datos cambiaron, no corresponde que siga publicado sin que lo veamos de nuevo.
        $needsReview = $place->status !== KosherPlace::STATUS_PENDING;

        $place->update(array_merge($validated, $needsReview ? [
            'status'           => KosherPlace::STATUS_PENDING,
            'rejection_reason' => null,
        ] : []));

        return redirect()->route('account.places')
            ->with('success', $needsReview
                ? 'Guardamos los cambios. Como se modificó, vuelve a quedar en revisión antes de publicarse.'
                : 'Guardamos los cambios.');
    }

    private function validatePlace(Request $request): array
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

        unset($validated['terms']);

        return $validated;
    }
}
