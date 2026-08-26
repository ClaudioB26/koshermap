<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\CatalogController;
use App\Http\Controllers\CategoryController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use App\Models\Country;
use App\Http\Controllers\Auth\GoogleAuthController;
use App\Http\Controllers\AccountController;

use App\Http\Controllers\ReviewController;
use App\Http\Controllers\ReportController;

// Favicon servido por ruta: el Document Root de producción apunta a public_html/
// en vez de public_html/public/, así que los estáticos de la raíz de public/ no
// se sirven solos. Ver doc/plan-adsense.md.
Route::get('/favicon.svg', function () {
    $svg = <<<'SVG'
<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 64 64">
  <rect width="64" height="64" rx="12" fill="#2563eb"/>
  <text x="32" y="45" font-family="system-ui,Segoe UI,Arial,sans-serif" font-size="38"
        font-weight="700" fill="#ffffff" text-anchor="middle">K</text>
</svg>
SVG;

    return response($svg, 200)
        ->header('Content-Type', 'image/svg+xml')
        ->header('Cache-Control', 'public, max-age=604800');
})->name('favicon');

// Robots.txt generado por ruta (no depende de servir el archivo estático en el hosting)
Route::get('/robots.txt', function () {
    $lines = [
        // /product/ y /brands/ NO se bloquean acá a propósito: devuelven 410 Gone
        // y un Disallow le impediría a Google entrar a verlo, dejándolas en el
        // limbo en vez de darlas de baja. Ver doc/plan-adsense.md.
        'User-agent: *',
        'Allow: /',
        'Disallow: /lang/',
        '',
        'User-agent: Googlebot',
        'Allow: /',
        '',
        'User-agent: Googlebot-Image',
        'Allow: /',
        '',
        'User-agent: Mediapartners-Google',
        'Allow: /',
        '',
        'User-agent: Bingbot',
        'Allow: /',
        '',
        'User-agent: Slurp',
        'Allow: /',
        '',
        'User-agent: DuckDuckBot',
        'Allow: /',
        '',
        'User-agent: Baiduspider',
        'Allow: /',
        '',
        'User-agent: YandexBot',
        'Allow: /',
        '',
        'User-agent: Applebot',
        'Allow: /',
        '',
        'User-agent: GPTBot',
        'Allow: /',
        '',
        'User-agent: ChatGPT-User',
        'Allow: /',
        '',
        'User-agent: OAI-SearchBot',
        'Allow: /',
        '',
        'User-agent: ClaudeBot',
        'Allow: /',
        '',
        'User-agent: anthropic-ai',
        'Allow: /',
        '',
        'User-agent: Google-Extended',
        'Allow: /',
        '',
        'User-agent: CCBot',
        'Allow: /',
        '',
        'User-agent: PerplexityBot',
        'Allow: /',
        '',
        'Sitemap: https://koshermap.org/sitemap.xml',
    ];

    return response(implode("\n", $lines), 200)->header('Content-Type', 'text/plain');
})->name('robots');

// Home apunta a /articulos durante el proceso de aprobación de AdSense
// (contenido editorial en vez de buscador). La búsqueda de productos
// se movió a /productos para no perder la funcionalidad.
Route::get('/', function () {
    return redirect()->route('articles.index', [], 301);
})->name('home');
Route::get('/productos', [SearchController::class, 'index'])->name('search.index');
// Fichas de producto retiradas de la web pública (410 Gone).
//
// Eran ~6.000 páginas templadas: de 5.968 productos activos solo 86 tenían
// una descripción real, y había 1 sola reseña en toda la base. Google rechazó
// el sitio 4 veces por "contenido de bajo valor" y esas fichas eran el 99% de
// las páginas. La búsqueda de /productos sigue funcionando igual: cada fila de
// resultado ya muestra certificadora, marca y estado kosher, que es la
// pregunta que el usuario viene a responder.
//
// Se usa 410 (no 404) para que Google las dé de baja rápido, y por eso NO
// deben bloquearse en robots.txt: un Disallow le impediría entrar a ver el
// 410 y quedarían en el limbo. Ver doc/plan-adsense.md.
//
// Los datos siguen en la base: esto es solo la capa pública.
Route::get('/product/{slug}', fn () => abort(410))->name('products.show');
Route::post('/product/{slug}/reviews', [ReviewController::class, 'store'])->name('reviews.store');
Route::post('/product/{product}/report', [ReportController::class, 'storeProduct'])->name('products.report');
Route::post('/places/{place}/report',   [ReportController::class, 'storePlace'])->name('places.report');

// Catálogo
Route::get('/categories', [CatalogController::class, 'categories'])->name('categories.index');
Route::get('/categories/{slug}', [CatalogController::class, 'category'])->name('categories.show');

Route::get('/countries', [CatalogController::class, 'countries'])->name('countries.index');
Route::get('/countries/{slug}', [CatalogController::class, 'country'])->name('countries.show');

Route::get('/certifiers', [CatalogController::class, 'certifiers'])->name('certifiers.index');
Route::middleware('auth')->group(function () {
    // Antes de /certifiers/{slug}: si no, "agregar" se interpreta como un slug de certificadora.
    Route::get('/certifiers/agregar', [\App\Http\Controllers\CertifierSubmissionController::class, 'create'])->name('certifiers.create');
    Route::post('/certifiers/agregar', [\App\Http\Controllers\CertifierSubmissionController::class, 'store'])->name('certifiers.store');
});
// La ficha por certificadora se fusiono en /certifiers (fila por fila, con
// toda la info): antes era indexable y paginaba su catalogo completo (ej.
// BDK Brasil: 1.167 productos en 59 paginas). 301 y no 410 porque el
// contenido no se borro, se mudo a /certifiers. Ver doc/plan-adsense.md.
Route::get('/certifiers/{slug}', fn () => redirect()->route('certifiers.index', [], 301))->name('certifiers.show');
Route::get('/certifiers/{slug}/contacto', [\App\Http\Controllers\ContactController::class, 'certifierContact'])->name('certifiers.contact');
Route::post('/certifiers/{slug}/contacto', [\App\Http\Controllers\ContactController::class, 'storeCertifierLead'])->name('certifiers.contact.store');

// Marcas retiradas junto con las fichas de producto: el listado era un hub de
// ~1.100 links sin contenido propio y cada ficha de marca solo repetía
// productos. El filtro por marca sigue disponible en /productos?brand=...
Route::get('/brands', fn () => abort(410))->name('brands.index');
Route::get('/brands/{slug}', fn () => abort(410))->name('brands.show');

// Listado público retirado temporalmente para la revisión de AdSense (410
// Gone). Es reversible: los datos y el resto del flujo (alta de locales,
// moderación admin, "mis locales") siguen intactos, solo se apaga la
// página pública. Ver doc/plan-adsense.md.
Route::get('/places', fn () => abort(410))->name('places.index');
Route::middleware('auth')->group(function () {
    Route::get('/places/agregar', [\App\Http\Controllers\PlaceSubmissionController::class, 'create'])->name('places.create');
    Route::post('/places/agregar', [\App\Http\Controllers\PlaceSubmissionController::class, 'store'])->name('places.store');
});

// Login con Google (dueños de locales / certificadoras)
Route::get('/login', function () {
    return Auth::check() ? redirect()->route('account.places') : view('auth.login');
})->name('login');

Route::get('/login/google', [GoogleAuthController::class, 'redirect'])->name('auth.google.redirect');
Route::get('/login/google/callback', [GoogleAuthController::class, 'callback'])->name('auth.google.callback');
Route::post('/logout', [GoogleAuthController::class, 'logout'])->name('logout');

Route::middleware('auth')->prefix('cuenta')->name('account.')->group(function () {
    Route::get('/mis-locales', [AccountController::class, 'places'])->name('places');
    Route::get('/mi-certificadora', [AccountController::class, 'certifier'])->name('certifiers.my');

    Route::get('/mis-productos', [\App\Http\Controllers\ProductSubmissionController::class, 'index'])->name('products');
    Route::get('/mis-productos/nuevo', [\App\Http\Controllers\ProductSubmissionController::class, 'create'])->name('products.create');
    Route::post('/mis-productos', [\App\Http\Controllers\ProductSubmissionController::class, 'store'])->name('products.store');
    Route::get('/mis-productos/{product}/editar', [\App\Http\Controllers\ProductSubmissionController::class, 'edit'])->name('products.edit');
    Route::put('/mis-productos/{product}', [\App\Http\Controllers\ProductSubmissionController::class, 'update'])->name('products.update');
    Route::post('/mis-productos/{product}/toggle', [\App\Http\Controllers\ProductSubmissionController::class, 'toggleActive'])->name('products.toggle');
});

// Categorías en árbol por certificadora
// Retirado (410): mismo patron que /certifiers/{slug} y /product — un arbol
// de categorias por certificadora con productos paginados (7 certificadoras x
// ~67 categorias x paginacion = potencialmente miles de paginas index,follow
// casi identicas). Nada en el sitio lo linkeaba ya (huerfano) y es redundante
// con /productos?certifier=X&category=Y, que ya existe y es noindex. Ver
// doc/plan-adsense.md.
Route::get('/certifiers/{certifierSlug}/categories', fn () => abort(410))->name('certifiers.categories.tree');
Route::get('/certifiers/{certifierSlug}/categories/{categorySlug}', fn () => abort(410))->name('certifiers.categories.show');
Route::get('/api/certifiers/{certifierSlug}/categories', [CategoryController::class, 'api'])->name('certifiers.categories.api');

// Geolocalización y preferencias de país
Route::get('/country/select', [GeoLocationController::class, 'selectCountry'])->name('country.select');
Route::post('/country/set/{countryCode}', [GeoLocationController::class, 'setCountry'])->name('country.set');
Route::post('/country/clear', [GeoLocationController::class, 'clearCountry'])->name('country.clear');

// APIs de geolocalización
Route::get('/api/geo/location', [GeoLocationController::class, 'getCurrentLocation'])->name('api.geo.location');
Route::get('/api/geo/certifiers/{countryCode}', [GeoLocationController::class, 'getLocalCertifiers'])->name('api.geo.certifiers');
Route::get('/api/geo/relevance/{certifierSlug}', [GeoLocationController::class, 'checkContentRelevance'])->name('api.geo.relevance');

// APIs de administración (solo para admin)
Route::get('/api/geo/stats', [GeoLocationController::class, 'getStats'])->name('api.geo.stats');
Route::post('/api/geo/clear-cache', [GeoLocationController::class, 'clearCache'])->name('api.geo.clear-cache');

// Admin
Route::prefix('admin')->name('admin.')->group(function () {
    // Login (sin auth)
    Route::get('/login',  [\App\Http\Controllers\Admin\AdminAuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [\App\Http\Controllers\Admin\AdminAuthController::class, 'login'])->name('login.post');

    // Rutas protegidas
    Route::middleware(['auth', 'admin.check'])->group(function () {
        Route::post('/logout', [\App\Http\Controllers\Admin\AdminAuthController::class, 'logout'])->name('logout');

        Route::get('/reports',                  [\App\Http\Controllers\Admin\ReportsAdminController::class, 'index'])->name('reports.index');
        Route::post('/reports/{report}/review', [\App\Http\Controllers\Admin\ReportsAdminController::class, 'review'])->name('reports.review');

        Route::get('/reviews',      [\App\Http\Controllers\Admin\ReviewsModerationController::class, 'index'])->name('reviews.index');
        Route::post('/reviews/bulk', [\App\Http\Controllers\Admin\ReviewsModerationController::class, 'bulkAction'])->name('reviews.bulk');

        Route::get('/certifiers',                  [\App\Http\Controllers\Admin\CertifiersModerationController::class, 'index'])->name('certifiers.index');
        Route::post('/certifiers/{certifier}/approve', [\App\Http\Controllers\Admin\CertifiersModerationController::class, 'approve'])->name('certifiers.approve');
        Route::post('/certifiers/{certifier}/reject',  [\App\Http\Controllers\Admin\CertifiersModerationController::class, 'reject'])->name('certifiers.reject');
        Route::post('/certifiers/{certifier}/tier',    [\App\Http\Controllers\Admin\CertifiersModerationController::class, 'updateTier'])->name('certifiers.tier');

        Route::get('/places',                   [\App\Http\Controllers\Admin\PlacesModerationController::class, 'index'])->name('places.index');
        Route::post('/places/bulk',             [\App\Http\Controllers\Admin\PlacesModerationController::class, 'bulkAction'])->name('places.bulk');
        Route::post('/places/{place}/approve',  [\App\Http\Controllers\Admin\PlacesModerationController::class, 'approve'])->name('places.approve');
        Route::post('/places/{place}/reject',   [\App\Http\Controllers\Admin\PlacesModerationController::class, 'reject'])->name('places.reject');
        Route::post('/places/{place}/pending',  [\App\Http\Controllers\Admin\PlacesModerationController::class, 'resetPending'])->name('places.pending');
        Route::post('/places/{place}/type',     [\App\Http\Controllers\Admin\PlacesModerationController::class, 'updateType'])->name('places.update-type');
        Route::post('/places/{place}/orientation', [\App\Http\Controllers\Admin\PlacesModerationController::class, 'updateOrientation'])->name('places.update-orientation');
    });
});

// Sitemaps
Route::get('/sitemap.xml', [App\Http\Controllers\SitemapController::class, 'index'])->name('sitemap.index');

// Sitemaps paginados (más específicos primero)
// products y brands quedan fuera: esas páginas ahora devuelven 410.
Route::get('/sitemap-categories-{page}.xml', [App\Http\Controllers\SitemapController::class, 'categories'])->name('sitemap.categories');
Route::get('/sitemap-certifiers-{page}.xml', [App\Http\Controllers\SitemapController::class, 'certifiers'])->name('sitemap.certifiers');
Route::get('/sitemap-pages.xml', [App\Http\Controllers\SitemapController::class, 'pages'])->name('sitemap.pages');
Route::get('/sitemap-articles.xml', [App\Http\Controllers\SitemapController::class, 'articles'])->name('sitemap.articles');

// Sitemap genérico (menos prioritario)
Route::get('/sitemap-{type}.xml', [App\Http\Controllers\SitemapController::class, 'show'])->name('sitemap.show');

// Set User Country Preference
Route::get('/set-country/{slug}', function ($slug) {
    $country = Country::where('slug', $slug)->firstOrFail();
    // Cookie valid for 1 year
    Cookie::queue('user_country', $slug, 60 * 24 * 365);
    return back();
})->name('set-country');

// Set Locale
Route::get('set-locale/{locale}', function ($locale) {
    if (in_array($locale, ['en', 'es', 'pt', 'fr', 'he', 'ru'])) {
        Session::put('locale', $locale);
    }
    return back();
})->name('set-locale');

// Redirect legacy /lang/{locale} URLs (indexed by Google before route rename)
Route::get('lang/{locale}', function ($locale) {
    if (in_array($locale, ['en', 'es', 'pt', 'fr', 'he', 'ru'])) {
        Session::put('locale', $locale);
    }
    return redirect('/', 301);
});

// Páginas informativas (multiidioma vía sesión)
$infoPages = [
    'que-es-kosher'   => 'que_es_kosher',
    'kashrut'         => 'kashrut',
    'judaismo'        => 'judaismo',
    'etiqueta-kosher' => 'etiqueta_kosher',
    'sobre-nosotros'  => 'sobre_nosotros',
    'privacidad'      => 'privacidad',
    'terminos'        => 'terminos',
    'aviso-legal'      => 'aviso_legal',
    'politica-cookies' => 'politica_cookies',
];
foreach ($infoPages as $slug => $pageKey) {
    Route::get("/{$slug}", function () use ($pageKey) {
        $content = trans("pages.{$pageKey}");
        abort_if(!is_array($content), 404);
        return view('pages.show', compact('content'));
    })->name("pages.{$slug}");
}

// Contacto: página con formulario (no usa el template genérico de pages.show)
Route::get('/contacto', function () {
    $content = trans('pages.contacto');
    abort_if(!is_array($content), 404);
    return view('pages.contacto', compact('content'));
})->name('pages.contacto');
Route::post('/contacto', [\App\Http\Controllers\ContactController::class, 'store'])->name('contact.store');

// Artículos sobre kashrut (tabla articles, multiidioma vía JSON)
// Español es el default: sin prefijo de idioma en la URL.
Route::get('/articulos', [\App\Http\Controllers\ArticleController::class, 'index'])->name('articles.index');
Route::get('/articulos/{slug}', [\App\Http\Controllers\ArticleController::class, 'show'])->name('articles.show');

// Resto de idiomas: URL propia con prefijo /{locale}/{palabra-traducida}/...
// para que Google indexe cada idioma por separado (antes todos compartían
// /articulos/{slug} y solo la sesión decidía qué texto se mostraba).
foreach (\App\Models\Article::LOCALE_PATHS as $locale => $word) {
    Route::get("/{$locale}/{$word}", function (\Illuminate\Http\Request $request) use ($locale) {
        return app(\App\Http\Controllers\ArticleController::class)->index($request, $locale);
    })->name("articles.index.{$locale}");

    Route::get("/{$locale}/{$word}/{slug}", function (\Illuminate\Http\Request $request, string $slug) use ($locale) {
        return app(\App\Http\Controllers\ArticleController::class)->show($request, $slug, $locale);
    })->name("articles.show.{$locale}");
}
