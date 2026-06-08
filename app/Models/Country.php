<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Country extends Model
{
    protected $fillable = ['name', 'code', 'slug', 'locale'];

    public function products()
    {
        return $this->belongsToMany(Product::class, 'product_country');
    }

    public function certifiers()
    {
        return $this->belongsToMany(Certifier::class, 'certifier_country');
    }

    public function cities()
    {
        return $this->hasMany(City::class);
    }
}
