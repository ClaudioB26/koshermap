<?php

namespace App\Http\Controllers;

use App\Models\KosherPlace;
use App\Models\Product;
use App\Models\Report;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function storeProduct(Request $request, Product $product)
    {
        $request->validate([
            'reason'      => 'required|string|max:100',
            'observation' => 'nullable|string|max:1000',
            'email'       => 'nullable|email|max:255',
        ]);

        $product->reports()->create([
            'email'       => $request->email,
            'reason'      => $request->reason,
            'observation' => $request->observation,
        ]);

        return back()->with('report_sent', true);
    }

    public function storePlace(Request $request, KosherPlace $place)
    {
        $request->validate([
            'reason'      => 'required|string|max:100',
            'observation' => 'nullable|string|max:1000',
            'email'       => 'nullable|email|max:255',
        ]);

        $place->reports()->create([
            'email'       => $request->email,
            'reason'      => $request->reason,
            'observation' => $request->observation,
        ]);

        // Un lugar reportado vuelve a pendiente para revisión
        if ($place->isApproved()) {
            $place->update(['status' => KosherPlace::STATUS_PENDING]);
        }

        return back()->with('report_sent', true);
    }
}
