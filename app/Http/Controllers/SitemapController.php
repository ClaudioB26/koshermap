<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Certifier;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Carbon\Carbon;

class SitemapController extends Controller
{
    public function index()
    {
        $sitemap = '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
        $sitemap .= '<sitemapindex xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";
        
        // Páginas estáticas
        $sitemap .= $this->createSitemapEntry(
            url('/sitemap-pages.xml'),
            Carbon::now()
        );
        
        // Calcular cantidad de páginas para cada tipo
        $productPages = ceil(Product::count() / 1000);
        $categoryPages = ceil(Category::count() / 500);
        $certifierPages = ceil(Certifier::count() / 100);
        $brandPages = ceil(Brand::whereNotNull('slug')->where('slug', '!=', '')->count() / 500);
        
        // Agregar sitemaps de productos
        for ($page = 1; $page <= $productPages; $page++) {
            $sitemap .= $this->createSitemapEntry(
                url("/sitemap-products-{$page}.xml"),
                Carbon::now()
            );
        }
        
        // Agregar sitemaps de categorías
        for ($page = 1; $page <= $categoryPages; $page++) {
            $sitemap .= $this->createSitemapEntry(
                url("/sitemap-categories-{$page}.xml"),
                Carbon::now()
            );
        }
        
        // Agregar sitemaps de certificadores
        for ($page = 1; $page <= $certifierPages; $page++) {
            $sitemap .= $this->createSitemapEntry(
                url("/sitemap-certifiers-{$page}.xml"),
                Carbon::now()
            );
        }
        
        // Agregar sitemaps de marcas
        for ($page = 1; $page <= $brandPages; $page++) {
            $sitemap .= $this->createSitemapEntry(
                url("/sitemap-brands-{$page}.xml"),
                Carbon::now()
            );
        }
        
        $sitemap .= '</sitemapindex>';
        
        return response($sitemap, 200)
            ->header('Content-Type', 'application/xml')
            ->header('Cache-Control', 'public, max-age=3600'); // Cache por 1 hora
    }

    public function show($type)
    {
        // Para compatibilidad con rutas antiguas
        if ($type === 'products') {
            return $this->generateProductsSitemap(1);
        } elseif ($type === 'categories') {
            return $this->generateCategoriesSitemap(1);
        } elseif ($type === 'certifiers') {
            return $this->generateCertifiersSitemap(1);
        } elseif ($type === 'brands') {
            return $this->generateBrandsSitemap(1);
        } elseif ($type === 'pages') {
            return $this->generatePagesSitemap();
        }
        
        abort(404);
    }
    
    public function products($page = 1)
    {
        return $this->generateProductsSitemap($page);
    }
    
    public function categories($page = 1)
    {
        return $this->generateCategoriesSitemap($page);
    }
    
    public function certifiers($page = 1)
    {
        return $this->generateCertifiersSitemap($page);
    }
    
    public function brands($page = 1)
    {
        return $this->generateBrandsSitemap($page);
    }
    
    public function pages()
    {
        return $this->generatePagesSitemap();
    }
    
    private function generateProductsSitemap($page)
    {
        $xml = $this->startSitemap();
        
        $offset = ($page - 1) * 1000;
        
        Product::orderBy('updated_at', 'desc')
            ->skip($offset)
            ->take(1000)
            ->chunk(100, function ($products) use (&$xml) {
                foreach ($products as $product) {
                    $xml .= $this->createUrlBlock(
                        route('products.show', $product->slug),
                        $product->updated_at->format('Y-m-d'),
                        '0.7',
                        'weekly'
                    );
                }
            });
        
        $xml .= '</urlset>';
        
        return response($xml, 200)
            ->header('Content-Type', 'application/xml')
            ->header('Cache-Control', 'public, max-age=3600'); // Cache por 1 hora
    }
    
    private function generateCategoriesSitemap($page)
    {
        $xml = $this->startSitemap();
        
        $offset = ($page - 1) * 500;
        
        Category::orderBy('updated_at', 'desc')
            ->skip($offset)
            ->take(500)
            ->chunk(100, function ($categories) use (&$xml) {
                foreach ($categories as $category) {
                    $xml .= $this->createUrlBlock(
                        route('categories.show', $category->slug),
                        $category->updated_at->format('Y-m-d'),
                        '0.6',
                        'weekly'
                    );
                }
            });
        
        $xml .= '</urlset>';
        
        return response($xml, 200)
            ->header('Content-Type', 'application/xml')
            ->header('Cache-Control', 'public, max-age=7200'); // Cache por 2 horas
    }
    
    private function generateCertifiersSitemap($page)
    {
        $xml = $this->startSitemap();
        
        $offset = ($page - 1) * 100;
        
        Certifier::orderBy('updated_at', 'desc')
            ->skip($offset)
            ->take(100)
            ->chunk(25, function ($certifiers) use (&$xml) {
                foreach ($certifiers as $certifier) {
                    $xml .= $this->createUrlBlock(
                        route('certifiers.show', $certifier->slug),
                        $certifier->updated_at->format('Y-m-d'),
                        '0.6',
                        'monthly'
                    );
                }
            });
        
        $xml .= '</urlset>';
        
        return response($xml, 200)
            ->header('Content-Type', 'application/xml')
            ->header('Cache-Control', 'public, max-age=86400'); // Cache por 1 día
    }
    
    private function generateBrandsSitemap($page)
    {
        $xml = $this->startSitemap();
        
        $offset = ($page - 1) * 500;
        
        Brand::whereNotNull('slug')->where('slug', '!=', '')
            ->orderBy('updated_at', 'desc')
            ->skip($offset)
            ->take(500)
            ->chunk(50, function ($brands) use (&$xml) {
                foreach ($brands as $brand) {
                    $xml .= $this->createUrlBlock(
                        route('brands.show', $brand->slug),
                        $brand->updated_at->format('Y-m-d'),
                        '0.5',
                        'monthly'
                    );
                }
            });
        
        $xml .= '</urlset>';
        
        return response($xml, 200)
            ->header('Content-Type', 'application/xml')
            ->header('Cache-Control', 'public, max-age=86400'); // Cache por 1 día
    }
    
    private function generatePagesSitemap()
    {
        $xml = $this->startSitemap();
        
        // Static pages
        $pages = [
            '/' => ['priority' => '1.0', 'changefreq' => 'daily'],
            '/search' => ['priority' => '0.8', 'changefreq' => 'daily'],
            '/categories' => ['priority' => '0.6', 'changefreq' => 'weekly'],
            '/countries' => ['priority' => '0.6', 'changefreq' => 'weekly'],
            '/certifiers' => ['priority' => '0.6', 'changefreq' => 'weekly'],
            '/brands' => ['priority' => '0.5', 'changefreq' => 'weekly'],
        ];
        
        foreach ($pages as $page => $options) {
            $xml .= $this->createUrlBlock(
                url($page),
                Carbon::now()->format('Y-m-d'),
                $options['priority'],
                $options['changefreq']
            );
        }
        
        $xml .= '</urlset>';
        
        return response($xml, 200)
            ->header('Content-Type', 'application/xml')
            ->header('Cache-Control', 'public, max-age=86400'); // Cache por 1 día
    }
    
    private function startSitemap()
    {
        return '<?xml version="1.0" encoding="UTF-8"?>' . "\n" .
               '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";
    }
    
    private function createUrlBlock($url, $lastmod, $priority, $changefreq)
    {
        $block = "  <url>\n";
        $block .= "    <loc>" . htmlspecialchars($url, ENT_XML1) . "</loc>\n";
        $block .= "    <lastmod>{$lastmod}</lastmod>\n";
        $block .= "    <priority>{$priority}</priority>\n";
        $block .= "    <changefreq>{$changefreq}</changefreq>\n";
        $block .= "  </url>\n";
        
        return $block;
    }
    
    private function createSitemapEntry($url, $lastmod)
    {
        return "  <sitemap>\n" .
               "    <loc>" . htmlspecialchars($url, ENT_XML1) . "</loc>\n" .
               "    <lastmod>" . $lastmod->format('Y-m-d') . "</lastmod>\n" .
               "  </sitemap>\n";
    }
}
