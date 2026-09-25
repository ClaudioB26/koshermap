<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Certifier;
use App\Models\CertifierLead;
use App\Support\LeadsCsv;
use Illuminate\Http\Request;

/**
 * Leads de "querer certificar mi empresa" que llegan por el formulario de cada
 * certificadora. Es el numero con el que despues se le demuestra a cada una
 * cuantos clientes se le mandaron (ver el plan de monetizacion B2B).
 */
class LeadsController extends Controller
{
    public function index(Request $request)
    {
        $certifierId = $request->integer('certifier') ?: null;

        $query = CertifierLead::with('certifier')->latest();
        if ($certifierId) {
            $query->where('certifier_id', $certifierId);
        }

        if ($request->query('export') === 'csv') {
            return LeadsCsv::download($query, true);
        }

        $leads = $query->paginate(30)->withQueryString();

        // whereHas + withCount en vez de having('leads_count', ...): produccion corre
        // con ONLY_FULL_GROUP_BY y HAVING sobre un alias de withCount ya rompio antes.
        $summary = Certifier::whereHas('leads')
            ->withCount([
                'leads',
                'leads as leads_30d_count' => fn ($q) => $q->where('created_at', '>=', now()->subDays(30)),
            ])
            ->orderByDesc('leads_count')
            ->get();

        return view('admin.leads.index', [
            'leads'       => $leads,
            'summary'     => $summary,
            'certifierId' => $certifierId,
            'total'       => CertifierLead::count(),
        ]);
    }
}
