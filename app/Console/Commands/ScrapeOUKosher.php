<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use App\Jobs\ProcessOUProduct;
use Illuminate\Support\Facades\Log;

class ScrapeOUKosher extends Command
{
    protected $signature = 'scrape:ou';
    protected $description = 'Scrapes the OU Kosher API for products and dispatches jobs to process them.';

    public function handle()
    {
        $this->info('Starting scrape of OU Kosher API.');
        $productCount = 0;

        // Loop through the alphabet to search for products
        foreach (range('a', 'z') as $letter) {
            $this->info("Scraping products starting with letter: {$letter}");
            $page = 1;
            $perPage = 100;

            while (true) {
                $this->info("Scraping page {$page} for letter '{$letter}'...");

                try {
                    $response = Http::withHeaders([
                        'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/96.0.4664.110 Safari/537.36'
                    ])->get('https://oukosher.org/wp-json/kosher-api/v1/loc/posts', [
                        'query' => $letter,
                        'limit' => $perPage,
                        'page' => $page,
                    ]);

                    if ($response->failed()) {
                        Log::error('OU API request failed', ['status' => $response->status(), 'body' => $response->body()]);
                        $this->error("API request failed. See logs for details.");
                        break; // Move to the next letter
                    }

                    $productsResponse = $response->json();
                    $products = $productsResponse['results'] ?? [];

                    if (empty($products)) {
                        $this->info("No more products found for letter '{$letter}'.");
                        break; // End pagination for this letter
                    }

                    foreach ($products as $product) {
                        Log::info('Raw product from OU API:', ['product' => $product]);

                        $productName = $product['product_name'] ?? 'Nombre Desconocido';
                        $brandName = $product['company'] ?? 'Marca Desconocida';
                        $status = $product['kosher_status'] ?? 'OU Kosher';

                        $productName = html_entity_decode($productName);
                        $brandName = html_entity_decode($brandName);

                        ProcessOUProduct::dispatch([
                            'name' => $productName,
                            'brand' => $brandName,
                            'status' => $status
                        ])->onQueue('scraping');

                        $productCount++;
                    }

                    $this->info("Dispatched " . count($products) . " jobs.");
                    
                    // Check if we are at the end for this letter
                    $totalResults = $productsResponse['total'] ?? 0;
                    if (($page * $perPage) >= $totalResults) {
                         $this->info("Reached the final page for letter '{$letter}'.");
                        break;
                    }

                    $page++;
                    sleep(random_int(1, 2));

                } catch (\Exception $e) {
                    $this->error("Failed to scrape API page {$page} for letter '{$letter}': " . $e->getMessage());
                    Log::error('Scraping Exception:', ['error' => $e]);
                    break; // Move to the next letter
                }
            }
        }

        $this->info("Scrape finished. Dispatched a total of {$productCount} jobs to the 'scraping' queue.");
        return 0;
    }
}
