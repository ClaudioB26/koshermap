<?php

namespace App\Console\Commands;

use App\Services\SlugService;
use App\Models\Product;
use App\Models\Brand;
use App\Models\Category;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class OptimizeSlugs extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'slugs:optimize {--batch=100 : Number of records to process per batch} {--force : Force update even if slug seems clean}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Optimize and clean slugs for better SEO and URL friendliness';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting slug optimization...');
        
        $batchSize = $this->option('batch');
        $force = $this->option('force');
        
        $this->info("Batch size: {$batchSize}");
        $this->info("Force update: " . ($force ? 'YES' : 'NO'));
        
        // Optimize product slugs
        $this->optimizeProductSlugs($batchSize, $force);
        
        // Optimize brand slugs
        $this->optimizeBrandSlugs($batchSize, $force);
        
        // Optimize category slugs
        $this->optimizeCategorySlugs($batchSize, $force);
        
        // Generate report
        $this->generateReport();
        
        $this->info('Slug optimization completed!');
    }
    
    private function optimizeProductSlugs($batchSize, $force)
    {
        $this->info("\n=== Optimizing Product Slugs ===");
        
        $query = Product::query();
        
        if (!$force) {
            // Only process products with problematic slugs
            $query->where(function($q) {
                $q->where('slug', 'like', '%-%-%-%')  // More than 3 dashes
                  ->orWhere('slug', 'like', '%-%-%-%-%') // More than 4 dashes
                  ->orWhere('slug', 'like', '%--%')      // Double dashes
                  ->orWhereRaw('LENGTH(slug) > 100');    // Very long slugs
            });
        }
        
        $total = $query->count();
        $this->info("Found {$total} products to optimize");
        
        if ($total === 0) {
            $this->info("No product slugs need optimization");
            return;
        }
        
        $processed = 0;
        $updated = 0;
        
        $query->chunk($batchSize, function($products) use (&$processed, &$updated) {
            foreach ($products as $product) {
                $processed++;
                
                $brandName = $product->brand ? $product->brand->name : null;
                $newSlug = SlugService::generateProductSlug($product->name, $brandName, $product->barcode);
                
                if ($newSlug !== $product->slug) {
                    $product->slug = $newSlug;
                    $product->save();
                    $updated++;
                    
                    $this->line("Updated product {$product->id}: {$product->slug}");
                }
                
                if ($processed % 10 === 0) {
                    $this->info("Processed {$processed} products, updated {$updated}");
                }
            }
        });
        
        $this->info("Product slugs optimization: {$updated}/{$processed} updated");
    }
    
    private function optimizeBrandSlugs($batchSize, $force)
    {
        $this->info("\n=== Optimizing Brand Slugs ===");
        
        $query = Brand::query();
        
        if (!$force) {
            $query->where(function($q) {
                $q->whereNull('slug')
                  ->orWhere('slug', '')
                  ->orWhere('slug', 'like', '%--%')
                  ->orWhereRaw('LENGTH(slug) > 50');
            });
        }
        
        $total = $query->count();
        $this->info("Found {$total} brands to optimize");
        
        if ($total === 0) {
            $this->info("No brand slugs need optimization");
            return;
        }
        
        $processed = 0;
        $updated = 0;
        
        $query->chunk($batchSize, function($brands) use (&$processed, &$updated) {
            foreach ($brands as $brand) {
                $processed++;
                
                $newSlug = SlugService::generateBrandSlug($brand->name);
                
                if ($newSlug !== $brand->slug) {
                    $brand->slug = $newSlug;
                    $brand->save();
                    $updated++;
                    
                    $this->line("Updated brand {$brand->id}: {$brand->slug}");
                }
                
                if ($processed % 10 === 0) {
                    $this->info("Processed {$processed} brands, updated {$updated}");
                }
            }
        });
        
        $this->info("Brand slugs optimization: {$updated}/{$processed} updated");
    }
    
    private function optimizeCategorySlugs($batchSize, $force)
    {
        $this->info("\n=== Optimizing Category Slugs ===");
        
        $query = Category::query();
        
        if (!$force) {
            $query->where(function($q) {
                $q->whereNull('slug')
                  ->orWhere('slug', '')
                  ->orWhere('slug', 'like', '%--%')
                  ->orWhereRaw('LENGTH(slug) > 50');
            });
        }
        
        $total = $query->count();
        $this->info("Found {$total} categories to optimize");
        
        if ($total === 0) {
            $this->info("No category slugs need optimization");
            return;
        }
        
        $processed = 0;
        $updated = 0;
        
        $query->chunk($batchSize, function($categories) use (&$processed, &$updated) {
            foreach ($categories as $category) {
                $processed++;
                
                $newSlug = SlugService::generateCategorySlug($category->name);
                
                if ($newSlug !== $category->slug) {
                    $category->slug = $newSlug;
                    $category->save();
                    $updated++;
                    
                    $this->line("Updated category {$category->id}: {$category->slug}");
                }
                
                if ($processed % 10 === 0) {
                    $this->info("Processed {$processed} categories, updated {$updated}");
                }
            }
        });
        
        $this->info("Category slugs optimization: {$updated}/{$processed} updated");
    }
    
    private function generateReport()
    {
        $this->info("\n=== OPTIMIZATION REPORT ===");
        
        // Product slug statistics
        $productStats = [
            'total' => Product::count(),
            'with_slug' => Product::whereNotNull('slug')->where('slug', '!=', '')->count(),
            'avg_length' => DB::table('products')->whereNotNull('slug')->avg(DB::raw('LENGTH(slug)')),
            'max_length' => DB::table('products')->max(DB::raw('LENGTH(slug)')),
            'duplicates' => Product::select('slug')->groupBy('slug')->havingRaw('COUNT(*) > 1')->count(),
        ];
        
        $this->info("Products:");
        $this->info("  Total: {$productStats['total']}");
        $this->info("  With slug: {$productStats['with_slug']}");
        $this->info("  Average length: " . round($productStats['avg_length'], 1) . " chars");
        $this->info("  Max length: {$productStats['max_length']} chars");
        $this->info("  Duplicates: {$productStats['duplicates']}");
        
        // Brand slug statistics
        $brandStats = [
            'total' => Brand::count(),
            'with_slug' => Brand::whereNotNull('slug')->where('slug', '!=', '')->count(),
            'duplicates' => Brand::select('slug')->groupBy('slug')->havingRaw('COUNT(*) > 1')->count(),
        ];
        
        $this->info("\nBrands:");
        $this->info("  Total: {$brandStats['total']}");
        $this->info("  With slug: {$brandStats['with_slug']}");
        $this->info("  Duplicates: {$brandStats['duplicates']}");
        
        // Category slug statistics
        $categoryStats = [
            'total' => Category::count(),
            'with_slug' => Category::whereNotNull('slug')->where('slug', '!=', '')->count(),
            'duplicates' => Category::select('slug')->groupBy('slug')->havingRaw('COUNT(*) > 1')->count(),
        ];
        
        $this->info("\nCategories:");
        $this->info("  Total: {$categoryStats['total']}");
        $this->info("  With slug: {$categoryStats['with_slug']}");
        $this->info("  Duplicates: {$categoryStats['duplicates']}");
        
        // Sample of optimized slugs
        $this->info("\n=== SAMPLE OPTIMIZED SLUGS ===");
        
        $sampleProducts = Product::latest()->take(3)->get(['id', 'name', 'slug']);
        foreach ($sampleProducts as $product) {
            $this->info("Product: {$product->name}");
            $this->info("  Slug: {$product->slug}");
            $this->info("  Valid: " . (SlugService::isValidSlug($product->slug) ? 'YES' : 'NO'));
            $this->info("");
        }
    }
}
