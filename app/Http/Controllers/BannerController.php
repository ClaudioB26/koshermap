<?php

namespace App\Http\Controllers;

use App\Models\Banner;
use App\Services\BannerService;
use Illuminate\Support\Facades\Storage;

/**
 * Parte publica de los banners: la imagen (se sirve desde storage, sin
 * symlink) y el clic (cuenta y redirige al anunciante).
 */
class BannerController extends Controller
{
    public function image(Banner $banner)
    {
        $disk = Storage::disk('public');
        abort_unless($disk->exists($banner->image_path), 404);

        return $disk->response($banner->image_path, null, [
            'Cache-Control'          => 'public, max-age=86400',
            'X-Content-Type-Options' => 'nosniff',
        ]);
    }

    public function click(Banner $banner, BannerService $service)
    {
        // Solo http(s): el link lo carga el admin, pero no redirigimos a nada raro.
        abort_unless(preg_match('#^https?://#i', $banner->target_url), 404);

        if ($service->shouldCount()) {
            Banner::whereKey($banner->id)->increment('clicks');
        }

        return redirect()->away($banner->target_url)
            ->header('X-Robots-Tag', 'noindex, nofollow')
            ->header('Cache-Control', 'no-store');
    }
}
