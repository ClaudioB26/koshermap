<?php

namespace App\Console\Commands;

use App\Models\Brand;
use App\Models\Certifier;
use App\Models\Country;
use App\Models\Product;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Symfony\Component\DomCrawler\Crawler;

class ScrapeKMD extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'scrape:kmd {--limit= : Limit the number of pages to scrape}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Scrape products from KMD Mexico (kosher.com.mx)';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting KMD Mexico scraping...');

        // Ensure Mexico country exists
        $country = Country::firstOrCreate(
            ['code' => 'MX'],
            ['name' => 'México', 'slug' => 'mexico']
        );

        // Ensure KMD certifier exists
        $certifier = Certifier::firstOrCreate(
            ['slug' => 'kmd-mexico'],
            [
                'name' => 'KMD México',
                'full_name' => 'Kosher Maguen David',
                'description' => 'La certificadora líder en México.',
                'website' => 'https://www.kosher.com.mx/',
            ]
        );
        
        // Attach country to certifier if not attached
        if (!$certifier->countries()->where('countries.id', $country->id)->exists()) {
            $certifier->countries()->attach($country->id);
        }

        $baseUrl = 'https://www.kosher.com.mx/productos/buscar';
        $page = 1;
        $hasMore = true;
        $limit = $this->option('limit') ? (int)$this->option('limit') : 1000;

        while ($hasMore && $page <= $limit) {
            $url = $page === 1 
                ? "{$baseUrl}?search_str=" 
                : "{$baseUrl}/page:{$page}?search_str=";

            $this->info("Fetching page {$page}: {$url}");

            try {
                $response = Http::withoutVerifying()
                    ->withHeaders([
                        'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36',
                    ])
                    ->get($url);

                if (!$response->successful()) {
                    $this->error("Failed to fetch page {$page}. Status: " . $response->status());
                    break;
                }

                $html = $response->body();
                $crawler = new Crawler($html);
                
                // Check if there are results
                $items = $crawler->filter('.u-repeater-1 .u-list-item');
                
                if ($items->count() === 0) {
                    $this->info("No more items found on page {$page}.");
                    $hasMore = false;
                    break;
                }

                $this->info("Found " . $items->count() . " items on page {$page}.");

                $items->each(function (Crawler $node) use ($country, $certifier) {
                    try {
                        $nameNode = $node->filter('h3');
                        $name = $nameNode->count() ? trim($nameNode->text()) : null;

                        $descNode = $node->filter('p');
                        $description = $descNode->count() ? $descNode->html() : '';
                        
                        // Parse description for Brand and Status
                        // Format: "Marca: BRAND<br>Estatus: STATUS ..."
                        $brandName = 'Unknown';
                        $status = '';
                        
                        if (preg_match('/Marca:\s*(.*?)<br>/i', $description, $matches)) {
                            $brandName = trim($matches[1]);
                        }
                        
                        if (preg_match('/Estatus:\s*(.*?)(<br>|$)/i', $description, $matches)) {
                            $status = trim($matches[1]);
                        }

                        if (!$name) return;

                        // Map status
                        $kosherStatus = 'Unknown';
                        $s = mb_strtolower($status);
                        if (str_contains($s, 'lacteo') || str_contains($s, 'lácteo') || str_contains($s, 'leche')) {
                            $kosherStatus = 'Dairy';
                        } elseif (str_contains($s, 'parve') || str_contains($s, 'pareve')) {
                            $kosherStatus = 'Pareve';
                        } elseif (str_contains($s, 'carne') || str_contains($s, 'cárnico') || str_contains($s, 'basari')) {
                            $kosherStatus = 'Meat';
                        } elseif (str_contains($s, 'pescado')) {
                            $kosherStatus = 'Pareve'; // Usually treated as pareve but with fish status
                        }

                        // Create or update Brand
                        $brandSlug = Str::slug($brandName);
                        $brand = Brand::where('slug', $brandSlug)->first();
                        if (!$brand) {
                            $brand = Brand::firstOrCreate(
                                ['name' => $brandName],
                                ['slug' => $brandSlug]
                            );
                        }

                        // Generate unique hash
                        $uniqueHash = md5('KMD_MX_' . $name . '_' . $brandName);
                        
                        // Generate unique slug
                        $baseSlug = Str::slug($name . '-' . $brandName . '-kmd');
                        $slug = $baseSlug;
                        $counter = 1;
                        while (Product::where('slug', $slug)->where('unique_hash', '!=', $uniqueHash)->exists()) {
                            $slug = $baseSlug . '-' . $counter;
                            $counter++;
                        }

                        $product = Product::updateOrCreate(
                            ['unique_hash' => $uniqueHash],
                            [
                                'name' => $name,
                                'slug' => $slug,
                                'brand_id' => $brand->id,
                                'description' => $status, // Storing original status string
                                'kosher_status' => $kosherStatus,
                                'source' => 'kmd_mx',
                                'certifier_id' => $certifier->id,
                                'barcode' => null, // KMD doesn't seem to provide barcodes
                                'image_url' => null,
                            ]
                        );
                        
                        // Sync country
                        if (!$product->countries()->where('countries.id', $country->id)->exists()) {
                            $product->countries()->attach($country->id);
                        }

                        // Attach certifier - Removed as we use certifier_id
                        // if (!$product->certifiers()->where('certifiers.id', $certifier->id)->exists()) {
                        //     $product->certifiers()->attach($certifier->id);
                        // }

                    } catch (\Exception $e) {
                        $this->error("Error processing item: " . $e->getMessage());
                    }
                });

                $page++;
                sleep(1); // Be nice to the server

            } catch (\Exception $e) {
                $this->error("Error fetching page {$page}: " . $e->getMessage());
                $hasMore = false;
            }
        }

        $this->info('Scraping completed.');
    }
}
