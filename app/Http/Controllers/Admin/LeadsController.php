<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Certifier;
use App\Models\CertifierLead;
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
            return $this->exportCsv($query);
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

    private function exportCsv($query)
    {
        $filename = 'leads-koshermap-' . now()->format('Y-m-d') . '.csv';

        return response()->streamDownload(function () use ($query) {
            $out = fopen('php://output', 'w');
            fwrite($out, "\xEF\xBB\xBF"); // BOM UTF-8: Excel reconoce los acentos
            fputcsv($out, ['Fecha', 'Certificadora', 'Empresa', 'Contacto', 'Email', 'Telefono', 'Producto', 'Mensaje'], ',', '"', '');

            foreach ($query->cursor() as $lead) {
                fputcsv($out, array_map([$this, 'safeCell'], [
                    $lead->created_at->format('Y-m-d H:i'),
                    $lead->certifier?->name,
                    $lead->company,
                    $lead->name,
                    $lead->email,
                    $lead->phone,
                    $lead->product_type,
                    $lead->message,
                ]), ',', '"', '');
            }

            fclose($out);
        }, $filename, ['Content-Type' => 'text/csv; charset=UTF-8']);
    }

    /**
     * Los datos los escribe cualquier visitante. Una celda que empiece con
     * = + - @ se interpreta como formula al abrir el CSV en Excel (inyeccion
     * de formulas), asi que se le antepone un apostrofe para que quede texto.
     */
    private function safeCell(?string $value): string
    {
        $value = (string) $value;

        return preg_match('/^[=+\-@\t\r]/', $value) ? "'" . $value : $value;
    }
}
