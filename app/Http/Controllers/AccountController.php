<?php

namespace App\Http\Controllers;

use App\Models\Certifier;
use App\Support\LeadsCsv;
use Illuminate\Http\Request;

class AccountController extends Controller
{
    public function places(Request $request)
    {
        $places = $request->user()->places()->with('city.country', 'certifier')->latest()->get();

        return view('account.places', compact('places'));
    }

    public function certifier(Request $request)
    {
        $certifier = Certifier::where('owner_id', $request->user()->id)->first();
        $leadsCount = $certifier?->leads()->count() ?? 0;

        return view('account.certifier', compact('certifier', 'leadsCount'));
    }

    /**
     * Los contactos de empresas que quieren certificarse, generados por el
     * formulario de esta certificadora. Cada certificadora ve solo los suyos.
     */
    public function certifierLeads(Request $request)
    {
        $certifier = Certifier::where('owner_id', $request->user()->id)->first();
        abort_unless($certifier && $certifier->isApproved(), 403);

        $query = $certifier->leads()->latest();

        if ($request->query('export') === 'csv') {
            return LeadsCsv::download($query, false);
        }

        $leads = $query->paginate(20)->withQueryString();

        return view('account.certifier_leads', compact('certifier', 'leads'));
    }
}
