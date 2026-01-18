<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Certifier extends Model
{
    protected $fillable = ['name', 'slug', 'logo_symbol']; // <--- Añade esto

    public function products()
    {
        return $this->hasMany(Product::class);
    }
}