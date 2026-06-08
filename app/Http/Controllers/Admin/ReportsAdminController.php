<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Report;
use Illuminate\Http\Request;

class ReportsAdminController extends Controller
{
    public function index(Request $request)
    {
        $status = $request->input('status', 'pending');

        $reports = Report::with('reportable')
            ->where('status', $status)
            ->latest()
            ->paginate(30)
            ->withQueryString();

        $counts = Report::selectRaw('status, count(*) as total')
            ->groupBy('status')
            ->pluck('total', 'status');

        return view('admin.reports.index', compact('reports', 'counts', 'status'));
    }

    public function review(Request $request, Report $report)
    {
        $report->update([
            'status'      => 'reviewed',
            'admin_notes' => $request->input('admin_notes'),
            'reviewed_at' => now(),
        ]);

        return back()->with('success', 'Reporte marcado como revisado.');
    }
}
