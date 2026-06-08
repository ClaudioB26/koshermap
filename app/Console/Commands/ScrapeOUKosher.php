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
        $verify = filter_var(env('HTTP_VERIFY_SSL', true), FILTER_VALIDATE_BOOLEAN);

        // Loop through the alphabet to search for products
        foreach (range('a', 'z') as $letter) {
            $this->info("Scraping products starting with letter: {$letter}");
            $page = 1;
            $perPage = 100;

            while (true) {
                $this->info("Scraping page {$page} for letter '{$letter}'...");

                try {
                    $response = Http::withHeaders([
                        'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/96.0.4664.110 Safari/537.36',
                        'Connection' => 'keep-alive',
                        'Accept' => 'application/json',
                        'Accept-Encoding' => 'gzip, deflate'
                    ])
                    ->withOptions([
                        'verify' => $verify,
                        'connect_timeout' => 10,
                        'read_timeout' => 60,
                        'timeout' => 90
                    ])
                    ->retry(2, 2000, function ($exception, $request) {
                        // Solo reintentar en timeouts o errores de conexión
                        return $exception instanceof \Illuminate\Http\Client\ConnectionException 
                               || $exception instanceof \Illuminate\Http\Client\RequestException;
                    })
                    ->get('https://oukosher.org/wp-json/kosher-api/v1/loc/posts', [
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

                        $productName = $product['LabelName'] ?? null;
                        $brandName = $product['BrandName'] ?? null;
                        $symbol = $product['Symbol'] ?? null;

                        if (!$productName || !$brandName) {
                            continue;
                        }

                        $productName = html_entity_decode($productName);
                        $brandName = html_entity_decode($brandName);

                        // Clean quotes from start/end
                        $productName = trim($productName, " \t\n\r\0\x0B\"'");
                        $brandName = trim($brandName, " \t\n\r\0\x0B\"'");

                        $status = 'OU Kosher';
                        $symbolLower = strtolower($symbol ?? '');
                        if (str_contains($symbolLower, 'd')) {
                            $status = 'OU Dairy';
                        } elseif (str_contains($symbolLower, 'pareve') || str_contains($symbolLower, 'p')) {
                            $status = 'OU Pareve';
                        } elseif (str_contains($symbolLower, 'fish')) {
                            $status = 'OU Fish';
                        }

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
                    
                    // Pausa progresiva para evitar sobrecargar la API
                    $pauseTime = min(5, max(2, $page * 0.5));
                    $this->info("Pausing for {$pauseTime} seconds before next page...");
                    sleep($pauseTime);

                } catch (\Illuminate\Http\Client\ConnectionException $e) {
                    $this->error("Connection timeout for page {$page} of letter '{$letter}'. Skipping to next letter.");
                    Log::warning('Connection timeout', ['letter' => $letter, 'page' => $page, 'error' => $e->getMessage()]);
                    break; // Move to the next letter
                } catch (\Illuminate\Http\Client\RequestException $e) {
                    $this->error("HTTP error for page {$page} of letter '{$letter}': " . $e->getMessage());
                    Log::error('HTTP request error', ['letter' => $letter, 'page' => $page, 'error' => $e->getMessage()]);
                    break; // Move to the next letter
                } catch (\Exception $e) {
                    $this->error("Unexpected error for page {$page} of letter '{$letter}': " . $e->getMessage());
                    Log::error('Unexpected scraping error', ['letter' => $letter, 'page' => $page, 'error' => $e]);
                    break; // Move to the next letter
                }
            }
        }

        $this->info("Scrape finished. Dispatched a total of {$productCount} jobs to the 'scraping' queue.");
        return 0;
    }
}
