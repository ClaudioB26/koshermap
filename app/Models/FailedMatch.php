<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class FailedMatch extends Model
{
    use HasFactory;

    protected $fillable = [
        'ou_product_name',
        'ou_brand_name',
        'search_term_used',
        'off_candidates',
        'best_score',
        'rejection_reason',
        'needs_human_review',
        'reviewed_at',
        'reviewed_by'
    ];

    protected $casts = [
        'off_candidates' => 'array',
        'best_score' => 'integer',
        'needs_human_review' => 'boolean',
        'reviewed_at' => 'datetime'
    ];

    /**
     * Scopes
     */
    public function scopeNeedsReview($query)
    {
        return $query->where('needs_human_review', true)->whereNull('reviewed_at');
    }

    public function scopeReviewed($query)
    {
        return $query->whereNotNull('reviewed_at');
    }

    public function scopeByReason($query, $reason)
    {
        return $query->where('rejection_reason', $reason);
    }

    /**
     * Marcar como revisado
     */
    public function markAsReviewed($reviewerId = 'system')
    {
        $this->update([
            'reviewed_at' => now(),
            'reviewed_by' => $reviewerId,
            'needs_human_review' => false
        ]);
    }

    /**
     * Crear mapeo a partir de este failed match
     */
    public function createMapping($candidateIndex, $status = 'manual_verified')
    {
        $candidates = $this->off_candidates ?? [];
        
        if (!isset($candidates[$candidateIndex])) {
            throw new \Exception('Candidate index not found');
        }

        $candidate = $candidates[$index];
        
        return OuOffMapping::create([
            'ou_product_name' => $this->ou_product_name,
            'ou_brand_name' => $this->ou_brand_name,
            'off_product_name' => $candidate['product_name'] ?? null,
            'off_brand_name' => $candidate['brands'] ?? null,
            'off_barcode' => $candidate['code'] ?? null,
            'off_image_url' => $candidate['image_url'] ?? null,
            'confidence_score' => $candidate['confidence_score']['total'] ?? 0,
            'match_status' => $status,
            'scoring_breakdown' => $candidate['confidence_score'] ?? [],
            'matched_by' => 'manual'
        ]);
    }

    /**
     * Estadísticas de failed matches
     */
    public static function getStats()
    {
        $total = self::count();
        $needsReview = self::needsReview()->count();
        $reviewed = self::reviewed()->count();
        
        $reasons = self::selectRaw('rejection_reason, COUNT(*) as count')
            ->groupBy('rejection_reason')
            ->orderByDesc('count')
            ->get()
            ->pluck('count', 'rejection_reason');

        return [
            'total_failed' => $total,
            'pending_review' => $needsReview,
            'reviewed' => $reviewed,
            'review_rate' => $total > 0 ? round(($reviewed / $total) * 100, 2) : 0,
            'rejection_reasons' => $reasons->toArray()
        ];
    }
}
