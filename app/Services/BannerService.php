<?php

namespace App\Services;

use App\Models\Banner;

/**
 * Elige que banner mostrar en un lugar de la pagina (arriba / abajo) y
 * cuenta la impresion. Los banners solo aparecen en articulos y en la
 * pagina de certificadoras.
 */
class BannerService
{
    public function pageGroup(): ?string
    {
        if (request()->routeIs('articles.*')) {
            return 'articles';
        }
        if (request()->routeIs('certifiers.index')) {
            return 'certifiers';
        }

        return null;
    }

    public function pick(string $slot): ?Banner
    {
        $group = $this->pageGroup();
        if (! $group) {
            return null;
        }

        // Un banner nunca debe tumbar una pagina: si la tabla todavia no existe
        // (deploy del codigo antes de correr la migracion) o la base falla, la
        // pagina sale igual, sin banner.
        try {
            $banner = Banner::live()
                ->where('slot', $slot)
                ->whereIn('pages', ['all', $group])
                ->inRandomOrder()
                ->first();

            if ($banner && $this->shouldCount()) {
                Banner::whereKey($banner->id)->increment('impressions');
            }

            return $banner;
        } catch (\Illuminate\Database\QueryException $e) {
            \Illuminate\Support\Facades\Log::warning('BannerService: no se pudo leer banners, se omite. ' . $e->getMessage());

            return null;
        }
    }

    /** No se cuentan bots ni al admin (que mira los banners para revisarlos). */
    public function shouldCount(): bool
    {
        return ! $this->isBot() && ! (auth()->check() && auth()->user()->isAdmin());
    }

    public function isBot(): bool
    {
        $ua = (string) request()->userAgent();

        return $ua === '' || (bool) preg_match('/bot|crawl|spider|slurp|mediapartners|facebookexternalhit|preview|headless|lighthouse|curl|wget|python-requests/i', $ua);
    }
}
