<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use App\Models\Product;
use App\Models\Category;
use App\Models\Certifier;
use App\Models\Brand;
use Carbon\Carbon;

class GenerateSitemap extends Command
{
    protected $signature = 'sitemap:generate {--output=public : Output location (public/storage)}';
    protected $description = 'Generate dynamic sitemap.xml for products, categories, and certifiers';

    public function handle()
    {
        $this->info('Generating sitemap...');
        
        $output = $this->option('output');
        $sitemapPath = $output === 'storage' ? 'sitemap.xml' : 'public/sitemap.xml';
        
        $xml = $this->generateSitemapXml();
        
        if ($output === 'storage') {
            Storage::disk('local')->put($sitemapPath, $xml);
            $this->info("Sitemap saved to storage/app/{$sitemapPath}");
        } else {
            file_put_contents(base_path($sitemapPath), $xml);
            $this->info("Sitemap saved to {$sitemapPath}");
        }
        
        $this->info('Sitemap generated successfully!');
        return 0;
    }
    
    private function generateSitemapXml()
    {
        $xml = '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
        $xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";
        
        // Homepage
        $xml .= $this->createUrlBlock(
            url('/'),
            Carbon::now()->format('Y-m-d'),
            '1.0',
            'daily'
        );
        
        // Static pages
        $staticPages = [
            '/search' => ['priority' => '0.8', 'changefreq' => 'daily'],
            '/about' => ['priority' => '0.6', 'changefreq' => 'monthly'],
            '/contact' => ['priority' => '0.5', 'changefreq' => 'monthly'],
        ];
        
        foreach ($staticPages as $page => $options) {
            $xml .= $this->createUrlBlock(
                url($page),
                Carbon::now()->format('Y-m-d'),
                $options['priority'],
                $options['changefreq']
            );
        }
        
        // Products
        $this->info('Adding products to sitemap...');
        Product::chunk(1000, function ($products) use (&$xml) {
            foreach ($products as $product) {
                $lastmod = $product->updated_at ?? $product->created_at;
                $xml .= $this->createUrlBlock(
                    route('products.show', $product->slug),
                    $lastmod->format('Y-m-d'),
                    '0.7',
                    'weekly'
                );
            }
        });
        
        // Categories
        $this->info('Adding categories to sitemap...');
        Category::chunk(100, function ($categories) use (&$xml) {
            foreach ($categories as $category) {
                $lastmod = $category->updated_at ?? $category->created_at;
                $xml .= $this->createUrlBlock(
                    route('categories.show', $category->slug),
                    $lastmod->format('Y-m-d'),
                    '0.6',
                    'weekly'
                );
            }
        });
        
        // Certifiers
        $this->info('Adding certifiers to sitemap...');
        Certifier::chunk(50, function ($certifiers) use (&$xml) {
            foreach ($certifiers as $certifier) {
                $lastmod = $certifier->updated_at ?? $certifier->created_at;
                $xml .= $this->createUrlBlock(
                    route('certifiers.show', $certifier->slug),
                    $lastmod->format('Y-m-d'),
                    '0.6',
                    'monthly'
                );
            }
        });
        
        // Brands
        $this->info('Adding brands to sitemap...');
        Brand::whereNotNull('slug')->where('slug', '!=', '')->chunk(100, function ($brands) use (&$xml) {
            foreach ($brands as $brand) {
                $lastmod = $brand->updated_at ?? $brand->created_at;
                $xml .= $this->createUrlBlock(
                    route('brands.show', $brand->slug),
                    $lastmod->format('Y-m-d'),
                    '0.5',
                    'monthly'
                );
            }
        });
        
        $xml .= '</urlset>';
        
        return $xml;
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
}
