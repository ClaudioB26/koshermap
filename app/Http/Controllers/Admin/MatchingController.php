<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\OuOffMapping;
use App\Models\FailedMatch;
use App\Services\IntelligentMatchingEngine;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MatchingController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('admin');
    }

    /**
     * Dashboard principal de matching
     */
    public function dashboard()
    {
        $stats = [
            'mappings' => OuOffMapping::getStats(),
            'failed' => FailedMatch::getStats()
        ];

        $recentMappings = OuOffMapping::with('relatedProducts')
            ->latest()
            ->take(10)
            ->get();

        $pendingReviews = FailedMatch::needsReview()
            ->latest()
            ->take(20)
            ->get();

        return view('admin.matching.dashboard', compact('stats', 'recentMappings', 'pendingReviews'));
    }

    /**
     * Lista de mapeos existentes
     */
    public function mappings(Request $request)
    {
        $query = OuOffMapping::with('relatedProducts');

        // Filtros
        if ($request->filled('status')) {
            $query->where('match_status', $request->status);
        }

        if ($request->filled('confidence_min')) {
            $query->where('confidence_score', '>=', $request->confidence_min);
        }

        if ($request->filled('has_barcode')) {
            if ($request->has_barcode === 'yes') {
                $query->whereNotNull('off_barcode');
            } else {
                $query->whereNull('off_barcode');
            }
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('ou_product_name', 'like', "%{$search}%")
                  ->orWhere('ou_brand_name', 'like', "%{$search}%")
                  ->orWhere('off_product_name', 'like', "%{$search}%")
                  ->orWhere('off_brand_name', 'like', "%{$search}%");
            });
        }

        $mappings = $query->latest()->paginate(50);

        return view('admin.matching.mappings', compact('mappings'));
    }

    /**
     * Failed matches pendientes de revisión
     */
    public function failedMatches(Request $request)
    {
        $query = FailedMatch::needsReview();

        if ($request->filled('reason')) {
            $query->byReason($request->reason);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('ou_product_name', 'like', "%{$search}%")
                  ->orWhere('ou_brand_name', 'like', "%{$search}%");
            });
        }

        $failedMatches = $query->latest()->paginate(50);

        return view('admin.matching.failed', compact('failedMatches'));
    }

    /**
     * Detalle de un failed match
     */
    public function failedMatchDetail($id)
    {
        $failedMatch = FailedMatch::findOrFail($id);
        
        return view('admin.matching.failed-detail', compact('failedMatch'));
    }

    /**
     * Aprobar manualmente un match
     */
    public function approveMatch(Request $request, $id)
    {
        $failedMatch = FailedMatch::findOrFail($id);
        $candidateIndex = $request->input('candidate_index');
        
        try {
            $mapping = $failedMatch->createMapping($candidateIndex, 'manual_verified');
            $failedMatch->markAsReviewed(Auth::id());

            // Sincronizar productos relacionados
            $mapping->syncRelatedProducts();

            return redirect()->route('admin.matching.failed')
                ->with('success', 'Match aprobado y sincronizado correctamente.');

        } catch (\Exception $e) {
            return back()->with('error', 'Error al aprobar el match: ' . $e->getMessage());
        }
    }

    /**
     * Rechazar todos los candidatos
     */
    public function rejectMatch($id)
    {
        $failedMatch = FailedMatch::findOrFail($id);
        $failedMatch->markAsReviewed(Auth::id());

        return redirect()->route('admin.matching.failed')
            ->with('info', 'Match rechazado. No se creará ningún mapeo.');
    }

    /**
     * Reintentar matching con parámetros ajustados
     */
    public function retryMatching(Request $request, $id)
    {
        $failedMatch = FailedMatch::findOrFail($id);
        
        $engine = new IntelligentMatchingEngine();
        $result = $engine->matchProduct(
            $failedMatch->ou_product_name,
            $failedMatch->ou_brand_name
        );

        if ($result['status'] === 'auto_matched') {
            return redirect()->route('admin.matching.failed')
                ->with('success', 'Matching automático exitoso en reintento.');
        } else {
            return redirect()->route('admin.matching.failed-detail', $id)
                ->with('warning', 'El reintento no mejoró los resultados. Requiere revisión manual.');
        }
    }

    /**
     * Editar un mapeo existente
     */
    public function editMapping($id)
    {
        $mapping = OuOffMapping::findOrFail($id);
        
        return view('admin.matching.edit', compact('mapping'));
    }

    /**
     * Actualizar un mapeo
     */
    public function updateMapping(Request $request, $id)
    {
        $mapping = OuOffMapping::findOrFail($id);
        
        $validated = $request->validate([
            'off_product_name' => 'nullable|string',
            'off_brand_name' => 'nullable|string',
            'off_barcode' => 'nullable|string|unique:ou_off_mappings,off_barcode,' . $id,
            'off_image_url' => 'nullable|url',
            'match_status' => 'required|in:auto_matched,manual_verified,pending_review,rejected'
        ]);

        $mapping->update($validated);
        $mapping->update(['matched_by' => 'manual']);

        // Sincronizar productos relacionados
        if ($mapping->wasChanged(['off_barcode', 'off_image_url'])) {
            $mapping->syncRelatedProducts();
        }

        return redirect()->route('admin.matching.mappings')
            ->with('success', 'Mapeo actualizado correctamente.');
    }

    /**
     * Eliminar un mapeo
     */
    public function deleteMapping($id)
    {
        $mapping = OuOffMapping::findOrFail($id);
        $mapping->delete();

        return redirect()->route('admin.matching.mappings')
            ->with('info', 'Mapeo eliminado.');
    }

    /**
     * API para obtener estadísticas en tiempo real
     */
    public function apiStats()
    {
        return response()->json([
            'mappings' => OuOffMapping::getStats(),
            'failed' => FailedMatch::getStats()
        ]);
    }

    /**
     * Exportar mapeos a CSV
     */
    public function exportMappings(Request $request)
    {
        $query = OuOffMapping::query();

        if ($request->filled('status')) {
            $query->where('match_status', $request->status);
        }

        $mappings = $query->get();

        $csv = \League\Csv\Writer::createFromPath('php://temp', 'r+');
        $csv->insertOne([
            'OU Product Name', 'OU Brand Name', 'OFF Product Name', 
            'OFF Brand Name', 'OFF Barcode', 'OFF Image URL', 
            'Confidence Score', 'Match Status', 'Created At'
        ]);

        foreach ($mappings as $mapping) {
            $csv->insertOne([
                $mapping->ou_product_name,
                $mapping->ou_brand_name,
                $mapping->off_product_name,
                $mapping->off_brand_name,
                $mapping->off_barcode,
                $mapping->off_image_url,
                $mapping->confidence_score,
                $mapping->match_status,
                $mapping->created_at->format('Y-m-d H:i:s')
            ]);
        }

        $csv->output('ou_off_mappings_' . date('Y-m-d') . '.csv');
        exit;
    }
}
