<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    protected $fillable = ['slug', 'category', 'title', 'excerpt', 'content', 'sort_order', 'is_published'];

    protected $casts = [
        'is_published' => 'boolean',
    ];

    public function scopePublished($query)
    {
        return $query->where('is_published', true);
    }

    public function getTitleAttribute($value)
    {
        return $this->decodeTranslated($value);
    }

    public function setTitleAttribute($value)
    {
        $this->attributes['title'] = is_array($value) ? json_encode($value, JSON_UNESCAPED_UNICODE) : $value;
    }

    public function getExcerptAttribute($value)
    {
        return $this->decodeTranslated($value);
    }

    public function setExcerptAttribute($value)
    {
        $this->attributes['excerpt'] = is_array($value) ? json_encode($value, JSON_UNESCAPED_UNICODE) : $value;
    }

    public function getContentAttribute($value)
    {
        return $this->decodeTranslated($value);
    }

    public function setContentAttribute($value)
    {
        $this->attributes['content'] = is_array($value) ? json_encode($value, JSON_UNESCAPED_UNICODE) : $value;
    }

    private function decodeTranslated(?string $value): ?string
    {
        if ($value === null) {
            return null;
        }

        $decoded = json_decode($value, true);
        if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
            $locale = app()->getLocale();
            return $decoded[$locale] ?? $decoded['es'] ?? $decoded['en'] ?? $value;
        }

        return $value;
    }
}
