<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
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

// Robots.txt generado por ruta (no depende de servir el archivo estático en el hosting)
Route::get('/robots.txt', function () {
    $lines = [
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

// Cambia la ruta '/' que estaba antes por esta:
Route::get('/', [SearchController::class, 'index'])->name('home');
// Ruta para ver un producto individual
Route::get('/product/{slug}', [ProductController::class, 'show'])->name('products.show');
Route::post('/product/{slug}/reviews', [ReviewController::class, 'store'])->name('reviews.store');
Route::post('/product/{product}/report', [ReportController::class, 'storeProduct'])->name('products.report');
Route::post('/places/{place}/report',   [ReportController::class, 'storePlace'])->name('places.report');

// Catálogo
Route::get('/categories', [CatalogController::class, 'categories'])->name('categories.index');
Route::get('/categories/{slug}', [CatalogController::class, 'category'])->name('categories.show');

Route::get('/countries', [CatalogController::class, 'countries'])->name('countries.index');
Route::get('/countries/{slug}', [CatalogController::class, 'country'])->name('countries.show');

Route::get('/certifiers', [CatalogController::class, 'certifiers'])->name('certifiers.index');
Route::get('/certifiers/{slug}', [CatalogController::class, 'certifier'])->name('certifiers.show');

Route::get('/brands', [CatalogController::class, 'brands'])->name('brands.index');
Route::get('/brands/{slug}', [CatalogController::class, 'brand'])->name('brands.show');

Route::get('/places', [CatalogController::class, 'placesIndex'])->name('places.index');
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
});

// Categorías en árbol por certificadora
Route::get('/certifiers/{certifierSlug}/categories', [CategoryController::class, 'tree'])->name('certifiers.categories.tree');
Route::get('/certifiers/{certifierSlug}/categories/{categorySlug}', [CategoryController::class, 'show'])->name('certifiers.categories.show');
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
    Route::middleware('auth')->group(function () {
        Route::post('/logout', [\App\Http\Controllers\Admin\AdminAuthController::class, 'logout'])->name('logout');

        Route::get('/reports',                  [\App\Http\Controllers\Admin\ReportsAdminController::class, 'index'])->name('reports.index');
        Route::post('/reports/{report}/review', [\App\Http\Controllers\Admin\ReportsAdminController::class, 'review'])->name('reports.review');

        Route::get('/reviews',      [\App\Http\Controllers\Admin\ReviewsModerationController::class, 'index'])->name('reviews.index');
        Route::post('/reviews/bulk', [\App\Http\Controllers\Admin\ReviewsModerationController::class, 'bulkAction'])->name('reviews.bulk');

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
Route::get('/sitemap-products-{page}.xml', [App\Http\Controllers\SitemapController::class, 'products'])->name('sitemap.products');
Route::get('/sitemap-categories-{page}.xml', [App\Http\Controllers\SitemapController::class, 'categories'])->name('sitemap.categories');
Route::get('/sitemap-certifiers-{page}.xml', [App\Http\Controllers\SitemapController::class, 'certifiers'])->name('sitemap.certifiers');
Route::get('/sitemap-brands-{page}.xml', [App\Http\Controllers\SitemapController::class, 'brands'])->name('sitemap.brands');
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
    'contacto'        => 'contacto',
    'privacidad'      => 'privacidad',
];
foreach ($infoPages as $slug => $pageKey) {
    Route::get("/{$slug}", function () use ($pageKey) {
        $content = trans("pages.{$pageKey}");
        abort_if(!is_array($content), 404);
        return view('pages.show', compact('content'));
    })->name("pages.{$slug}");
}

// Artículos sobre kashrut (tabla articles, multiidioma vía JSON)
Route::get('/articulos', [\App\Http\Controllers\ArticleController::class, 'index'])->name('articles.index');
Route::get('/articulos/{slug}', [\App\Http\Controllers\ArticleController::class, 'show'])->name('articles.show');
