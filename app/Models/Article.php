<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    protected $fillable = ['slug', 'slugs', 'category', 'title', 'excerpt', 'content', 'sort_order', 'is_published'];

    protected $casts = [
        'is_published' => 'boolean',
        'slugs'        => 'array',
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

    // El español es el idioma por defecto: no lleva prefijo en la URL
    // ("/articulos/slug"). El resto llevan "/{prefijo}/{palabra}/{slug}".
    public const LOCALE_PATHS = [
        'en' => 'articles',
        'pt' => 'artigos',
        'fr' => 'articles',
        'ru' => 'статьи',
        'he' => 'מאמרים',
    ];

    public const SUPPORTED_LOCALES = ['es', 'en', 'pt', 'fr', 'ru', 'he'];

    // Texto del link "Artículos" en el breadcrumb, por idioma.
    public const SECTION_LABELS = [
        'es' => 'Artículos',
        'en' => 'Articles',
        'pt' => 'Artigos',
        'fr' => 'Articles',
        'ru' => 'Статьи',
        'he' => 'מאמרים',
    ];

    public static function sectionLabelFor(string $locale): string
    {
        return self::SECTION_LABELS[$locale] ?? self::SECTION_LABELS['es'];
    }

    /**
     * URL del listado de artículos (/articulos, /en/articles, etc.) para un
     * idioma puntual, sin depender del locale actual de la app.
     */
    public static function indexUrlFor(string $locale): string
    {
        if ($locale === 'es') {
            return url('/articulos');
        }

        $pathWord = self::LOCALE_PATHS[$locale] ?? 'articles';

        return url('/' . $locale . '/' . $pathWord);
    }

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

    /**
     * Slug del artículo en un idioma puntual, sin depender del locale actual
     * de la app. Español vive en la columna 'slug'; el resto en 'slugs' (json).
     */
    public function slugFor(string $locale): ?string
    {
        if ($locale === 'es') {
            return $this->slug;
        }

        return $this->slugs[$locale] ?? null;
    }

    /**
     * Título crudo en un idioma puntual, sin pasar por el locale actual de la
     * app. Se usa para generar slugs y para las etiquetas hreflang.
     */
    public function rawTitleFor(string $locale): ?string
    {
        $decoded = json_decode($this->getRawOriginal('title'), true);
        if (!is_array($decoded)) {
            return $this->getRawOriginal('title');
        }

        return $decoded[$locale] ?? $decoded['es'] ?? null;
    }

    /**
     * URL absoluta del artículo en un idioma puntual. Español no lleva
     * prefijo; el resto van bajo /{locale}/{palabra-traducida}/{slug}.
     * Devuelve null si todavía no existe un slug para ese idioma.
     */
    public function urlFor(string $locale): ?string
    {
        $slug = $this->slugFor($locale);
        if (!$slug) {
            return null;
        }

        if ($locale === 'es') {
            return url('/articulos/' . $slug);
        }

        $pathWord = self::LOCALE_PATHS[$locale] ?? 'articles';

        // Se concatena la palabra y el slug tal cual (UTF-8), sin url-encodear
        // a mano: el navegador se encarga al armar el request, y así el href
        // queda legible en el código fuente para ruso/hebreo.
        return url('/' . $locale . '/' . $pathWord . '/' . $slug);
    }

    /**
     * Todas las URLs disponibles del artículo, indexadas por idioma.
     * Para usar en las etiquetas <link rel="alternate" hreflang="...">.
     */
    public function alternateUrls(): array
    {
        $urls = [];
        foreach (self::SUPPORTED_LOCALES as $locale) {
            $url = $this->urlFor($locale);
            if ($url) {
                $urls[$locale] = $url;
            }
        }

        return $urls;
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
