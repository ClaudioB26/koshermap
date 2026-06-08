<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Review;
use Illuminate\Http\Request;

class ReviewsModerationController extends Controller
{
    public function index(Request $request)
    {
        $status = $request->input('status', 'pending');

        $query = Review::with('product')->latest();

        if ($status === 'flagged') {
            // Pendientes con contenido marcado como feo
            $query->where('status', Review::STATUS_PENDING)->where('flagged', true);
        } elseif ($status === 'pending') {
            // Pendientes limpios (sin flag)
            $query->where('status', Review::STATUS_PENDING)->where('flagged', false);
        } else {
            $query->where('status', $status);
        }

        $reviews = $query->paginate(30)->withQueryString();

        $counts = [
            'pending'  => Review::where('status', Review::STATUS_PENDING)->where('flagged', false)->count(),
            'flagged'  => Review::where('status', Review::STATUS_PENDING)->where('flagged', true)->count(),
            'approved' => Review::approved()->count(),
            'rejected' => Review::rejected()->count(),
        ];

        // Palabras sugeridas para bannear (de los rejection_notes no vacíos)
        $suggestedWords = Review::rejected()
            ->whereNotNull('rejection_note')
            ->where('rejection_note', '!=', '')
            ->pluck('rejection_note')
            ->unique()
            ->values();

        return view('admin.reviews.index', compact('reviews', 'status', 'counts', 'suggestedWords'));
    }

    public function bulkAction(Request $request)
    {
        $request->validate([
            'action'         => ['required', 'in:approve,reject'],
            'ids'            => ['required', 'array', 'min:1'],
            'ids.*'          => ['integer', 'exists:reviews,id'],
            'rejection_note' => ['nullable', 'string', 'max:100'],
        ]);

        $action        = $request->input('action');
        $ids           = $request->input('ids');
        $rejectionNote = trim($request->input('rejection_note', ''));

        if ($action === 'approve') {
            Review::whereIn('id', $ids)->update([
                'status'      => Review::STATUS_APPROVED,
                'approved_at' => now(),
                'is_approved' => true,
            ]);
            $msg = count($ids) . ' comentario(s) aprobado(s).';
        } else {
            $data = [
                'status'      => Review::STATUS_REJECTED,
                'is_approved' => false,
            ];
            if ($rejectionNote !== '') {
                $data['rejection_note'] = $rejectionNote;
            }
            Review::whereIn('id', $ids)->update($data);
            $msg = count($ids) . ' comentario(s) rechazado(s).';
            if ($rejectionNote) {
                $msg .= " Palabra anotada: «{$rejectionNote}»";
            }
        }

        return redirect()->route('admin.reviews.index', ['status' => 'pending'])
            ->with('success', $msg);
    }
}
