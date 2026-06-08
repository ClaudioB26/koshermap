<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Certifier extends Model
{
    protected $fillable = ['name', 'slug', 'logo_symbol'];

    public function products()
    {
        return $this->hasMany(Product::class);
    }

    /**
     * Relación: Una certificadora tiene cobertura en varios Países.
     */
    public function countries()
    {
        return $this->belongsToMany(Country::class, 'certifier_country');
    }
}
