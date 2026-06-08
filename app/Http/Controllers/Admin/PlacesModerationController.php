<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Country;
use App\Models\KosherPlace;
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

        return view('admin.places.index', compact('places', 'counts', 'status', 'countries', 'country', 'type'));
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
