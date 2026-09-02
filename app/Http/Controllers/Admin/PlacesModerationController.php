<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Country;
use App\Models\KosherPlace;
use App\Models\PlaceTierPayment;
use App\Services\Billing\TierPricingService;
use Illuminate\Http\Request;

class PlacesModerationController extends Controller
{
    public function index(Request $request)
    {
        $status  = $request->input('status', 'pending');
        $country = $request->input('country');
        $type    = $request->input('type');

        $query = KosherPlace::with('city.country')
            ->where('status', $status)
            ->orderBy('created_at', 'desc');

        if ($country) {
            $query->whereHas('city.country', fn ($q) => $q->where('code', $country));
        }

        if ($type) {
            $query->where('place_type', $type);
        }

        $places = $query->paginate(30)->withQueryString();

        $counts = KosherPlace::selectRaw('status, count(*) as total')
            ->groupBy('status')
            ->pluck('total', 'status');

        $countries = Country::whereHas('cities.kosherPlaces')->orderBy('name')->get();

        $pendingTransfers = PlaceTierPayment::with('place')
            ->where('payment_method', PlaceTierPayment::METHOD_TRANSFER)
            ->where('status', PlaceTierPayment::STATUS_PENDING)
            ->orderBy('created_at')
            ->get();

        return view('admin.places.index', compact('places', 'counts', 'status', 'countries', 'country', 'type', 'pendingTransfers'));
    }

    public function updateTier(Request $request, KosherPlace $place)
    {
        $request->validate([
            'tier' => 'required|in:' . implode(',', [KosherPlace::TIER_FREE, KosherPlace::TIER_DESTACADA_RUBRO, KosherPlace::TIER_PREMIUM]),
        ]);

        $place->update(['tier' => $request->tier]);

        return back()->with('success', "Plan de \"$place->name\" actualizado a {$request->tier}.");
    }

    public function approveTransfer(PlaceTierPayment $payment)
    {
        $payment->update(['status' => PlaceTierPayment::STATUS_APPROVED]);

        $place = $payment->place;
        $place->update([
            'tier'                  => $payment->tier,
            'tier_expires_at'       => TierPricingService::nextExpiry($place->tier_expires_at, $payment->months),
            'tier_reminder_sent_at' => null,
        ]);

        return back()->with('success', "Transferencia aprobada, plan de \"{$payment->place->name}\" activado.");
    }

    public function rejectTransfer(PlaceTierPayment $payment)
    {
        $payment->update(['status' => PlaceTierPayment::STATUS_REJECTED]);

        return back()->with('success', "Transferencia de \"{$payment->place->name}\" rechazada.");
    }

    public function approve(KosherPlace $place)
    {
        $place->update(['status' => KosherPlace::STATUS_APPROVED, 'rejection_reason' => null]);

        return back()->with('success', "\"$place->name\" aprobado.");
    }

    public function reject(Request $request, KosherPlace $place)
    {
        $place->update([
            'status'           => KosherPlace::STATUS_REJECTED,
            'rejection_reason' => $request->input('reason'),
        ]);

        return back()->with('success', "\"$place->name\" rechazado.");
    }

    public function updateType(Request $request, KosherPlace $place)
    {
        $request->validate([
            'place_type' => 'required|in:' . implode(',', array_keys(KosherPlace::types())),
        ]);

        $place->update(['place_type' => $request->input('place_type')]);

        return back()->with('success', "Tipo de \"$place->name\" actualizado a {$request->input('place_type')}.");
    }

    public function updateOrientation(Request $request, KosherPlace $place)
    {
        $request->validate([
            'orientation' => 'required|in:' . implode(',', array_keys(KosherPlace::orientations())),
        ]);

        $place->update(['orientation' => $request->input('orientation')]);

        return back()->with('success', "Orientación de \"$place->name\" actualizada.");
    }

    public function resetPending(KosherPlace $place)
    {
        $place->update(['status' => KosherPlace::STATUS_PENDING, 'rejection_reason' => null]);

        return back()->with('success', "\"$place->name\" vuelto a pendiente.");
    }

    public function bulkAction(Request $request)
    {
        $request->validate([
            'action' => 'required|in:approve,reject',
            'ids'    => 'required|array|min:1',
            'ids.*'  => 'integer|exists:kosher_places,id',
        ]);

        $places = KosherPlace::whereIn('id', $request->ids)->get();
        $count  = $places->count();

        if ($request->action === 'approve') {
            $places->each(fn ($p) => $p->update(['status' => KosherPlace::STATUS_APPROVED, 'rejection_reason' => null]));
            return back()->with('success', "{$count} lugar(es) aprobados.");
        }

        $places->each(fn ($p) => $p->update([
            'status'           => KosherPlace::STATUS_REJECTED,
            'rejection_reason' => $request->input('reason'),
        ]));

        return back()->with('success', "{$count} lugar(es) rechazados.");
    }
}
