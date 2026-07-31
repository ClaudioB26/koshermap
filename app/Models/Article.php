<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    protected $fillable = ['slug', 'category', 'title', 'excerpt', 'content', 'sort_order', 'is_published'];

    protected $casts = [
        'is_published' => 'boolean',
    ];

    // Emoji de respaldo por categoría, para los artículos que todavía no tienen foto.
    public const CATEGORY_ICONS = [
        'halajot'        => '📜',
        'kasherizacion'  => '🔥',
        'festividades'   => '🕯️',
        'productos'      => '🏷️',
        'kashrut-basico' => '📖',
        'vida-diaria'    => '🏠',
    ];

    public function scopePublished($query)
    {
        return $query->where('is_published', true);
    }

    /**
     * Primera imagen del contenido, para usar como miniatura en los listados.
     * Se extrae del HTML en vez de guardarse aparte para que nunca quede
     * desincronizada con el artículo. Devuelve null si el artículo no tiene fotos.
     */
    public function getThumbnailAttribute(): ?string
    {
        if (!preg_match('/<img[^>]+src=["\']([^"\']+)["\']/i', $this->content ?? '', $m)) {
            return null;
        }

        return $m[1];
    }

    public function getCategoryIconAttribute(): string
    {
        return self::CATEGORY_ICONS[$this->category] ?? '📰';
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
