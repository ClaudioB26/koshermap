<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $fillable = ['name', 'slug', 'parent_id'];

    // We removed 'name' => 'array' cast because we handle it in accessor to return string by default
    protected $casts = []; 

    /**
     * Get the name of the category (translated if possible).
     */
    public function getNameAttribute($value)
    {
        // $value comes raw from DB.
        // If we use cast, $this->attributes['name'] is raw, but accessing $this->name uses cast value (array).
        // However, we want to return a string by default for compatibility.
        
        // Actually, let's NOT cast it to array in $casts if we want to override the accessor to return string.
        // OR we cast it, but then accessing $category->name returns array, which breaks views expecting string.
        
        // Strategy: 
        // 1. Keep $casts = ['name' => 'array']. 
        // 2. Add getTranslatedNameAttribute() for explicit usage.
        // 3. BUT current views use {{ $category->name }}. This will break.
        
        // Strategy 2:
        // Do NOT cast in $casts.
        // Decode manually in accessor.
        
        $decoded = json_decode($value, true);
        if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
             $locale = app()->getLocale();
             return $decoded[$locale] ?? $decoded['es'] ?? $decoded['en'] ?? $value;
        }
        return $value;
    }
    
    // Helper to set name as JSON
    public function setNameAttribute($value)
    {
        if (is_array($value)) {
            $this->attributes['name'] = json_encode($value);
        } else {
            $this->attributes['name'] = $value;
        }
    }

    public function products()
    {
        return $this->hasMany(Product::class);
    }
    
    public function parent()
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(Category::class, 'parent_id');
    }

    /**
     * Devuelve el id de esta categoría y de todos sus descendientes (a cualquier profundidad).
     */
    public function selfAndDescendantIds(): array
    {
        $ids = [$this->id];

        foreach ($this->children as $child) {
            $ids = array_merge($ids, $child->loadMissing('children')->selfAndDescendantIds());
        }

        return $ids;
    }
}
