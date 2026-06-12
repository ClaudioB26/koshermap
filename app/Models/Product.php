<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    /**
     * Los campos que se pueden llenar masivamente.
     */
    protected $fillable = [
        'name',
        'slug',
        'barcode',
        'kosher_status',
        'brand_id',
        'certifier_id',
        'category_id',
        'description',
        'image_url',
        'source',
        'unique_hash',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function reports()
    {
        return $this->morphMany(Report::class, 'reportable');
    }

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

    /**
     * Relación: Un producto pertenece a un Rubro/Categoría.
     */
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Relación: Un producto puede estar disponible en varios Países.
     */
    public function countries()
    {
        return $this->belongsToMany(Country::class, 'product_country');
    }

    /**
     * Relación: Un producto tiene muchos comentarios.
     */
    public function reviews()
    {
        return $this->hasMany(Review::class)->where('is_approved', true)->latest();
    }
}
