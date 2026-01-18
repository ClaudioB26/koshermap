<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    /**
     * Los campos que se pueden llenar masivamente.
     * Esto es vital para cuando hagamos el Scraper o importación.
     */
    protected $fillable = [
        'name',
        'slug',
        'barcode',
        'kosher_status',
        'brand_id',
        'certifier_id',
        'description',
        'image_url'
    ];

    /**
     * Relación: Un producto pertenece a una Marca.
     */
    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }

    /**
     * Relación: Un producto pertenece a una Certificadora (ej: OU, Star-K).
     */
    public function certifier()
    {
        return $this->belongsTo(Certifier::class);
    }
}