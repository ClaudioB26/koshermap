<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class OuOffMapping extends Model
{
    use HasFactory;

    protected $fillable = [
        'ou_product_name',
        'ou_brand_name',
        'off_product_name',
        'off_brand_name',
        'off_barcode',
        'off_image_url',
        'confidence_score',
        'match_status',
        'scoring_breakdown',
        'matched_by'
    ];

    protected $casts = [
        'scoring_breakdown' => 'array',
        'confidence_score' => 'integer'
    ];

    /**
     * Scopes para consultas comunes
     */
    public function scopeAutoMatched($query)
    {
        return $query->where('match_status', 'auto_matched');
    }

    public function scopeManualVerified($query)
    {
        return $query->where('match_status', 'manual_verified');
    }

    public function scopePendingReview($query)
    {
        return $query->where('match_status', 'pending_review');
    }

    public function scopeHighConfidence($query, $threshold = 80)
    {
        return $query->where('confidence_score', '>=', $threshold);
    }

    public function scopeWithBarcode($query)
    {
        return $query->whereNotNull('off_barcode');
    }

    public function scopeWithImage($query)
    {
        return $query->whereNotNull('off_image_url');
    }

    /**
     * Obtener productos relacionados
     */
    public function getRelatedProducts()
    {
        return Product::where('name', $this->ou_product_name)
            ->whereHas('brand', function($query) {
                $query->where('name', $this->ou_brand_name);
            })
            ->get();
    }

    /**
     * Actualizar productos relacionados con datos de OFF
     */
    public function syncRelatedProducts()
    {
        $products = $this->getRelatedProducts();
        
        foreach ($products as $product) {
            $updateData = [];
            
            if ($this->off_barcode && !$product->barcode) {
                $updateData['barcode'] = $this->off_barcode;
            }
            
            if ($this->off_image_url && !$product->image_url) {
                $updateData['image_url'] = $this->off_image_url;
            }
            
            if (!empty($updateData)) {
                $product->update($updateData);
            }
        }
    }

    /**
     * Estadísticas del matching
     */
    public static function getStats()
    {
        $total = self::count();
        $autoMatched = self::autoMatched()->count();
        $manualVerified = self::manualVerified()->count();
        $pendingReview = self::pendingReview()->count();
        $withBarcode = self::withBarcode()->count();
        $withImage = self::withImage()->count();
        
        $avgConfidence = self::avg('confidence_score') ?? 0;
        
        return [
            'total_mappings' => $total,
            'auto_matched' => $autoMatched,
            'manual_verified' => $manualVerified,
            'pending_review' => $pendingReview,
            'with_barcode' => $withBarcode,
            'with_image' => $withImage,
            'barcode_coverage' => $total > 0 ? round(($withBarcode / $total) * 100, 2) : 0,
            'image_coverage' => $total > 0 ? round(($withImage / $total) * 100, 2) : 0,
            'avg_confidence' => round($avgConfidence, 2),
            'automation_rate' => $total > 0 ? round(($autoMatched / $total) * 100, 2) : 0
        ];
    }
}
