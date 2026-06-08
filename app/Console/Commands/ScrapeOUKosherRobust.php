<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use App\Jobs\ProcessOUProductIntelligent;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;

class ScrapeOUKosherRobust extends Command
{
    protected $signature = 'scrape:ou-robust {--resume : Resume from last successful letter} {--letters= : Specific letters to scrape (comma-separated)} {--timeout=90 : Request timeout in seconds} {--reset : Reset all progress and start fresh} {--status : Show current progress status}';
    protected $description = 'Robust OU Kosher API scraper with error recovery and progressive delays.';

    private $timeout;
    private $failedLetters = [];
    private $successfulLetters = [];

    public function handle()
    {
        // Mostrar estado actual
        if ($this->option('status')) {
            $this->showStatus();
            return 0;
        }

        // Resetear progreso
        if ($this->option('reset')) {
            $this->resetProgress();
            return 0;
        }

        $this->timeout = $this->option('timeout');
        $this->info('Starting robust OU Kosher API scrape...');
        $this->info("Timeout: {$this->timeout}s | Resume: " . ($this->option('resume') ? 'YES' : 'NO'));

        $productCount = 0;
        $verify = filter_var(env('HTTP_VERIFY_SSL', true), FILTER_VALIDATE_BOOLEAN);

        // Determinar letras a procesar
        $letters = $this->getLettersToProcess();

        if (empty($letters)) {
            $this->info('✅ All letters have been completed!');
            return 0;
        }

        $this->info('Letters to process: ' . implode(', ', $letters));

        foreach ($letters as $letter) {
            $this->info("\n" . str_repeat("=", 50));
            $this->info("Processing letter: {$letter}");
            $this->info(str_repeat("=", 50));

            $letterCount = $this->scrapeLetter($letter, $verify);
            $productCount += $letterCount;

            if ($letterCount > 0) {
                $this->successfulLetters[] = $letter;
                
                // Guardar progreso en múltiples cachés
                Cache::put('ou_scraper_last_success', $letter, now()->addHours(24));
                
                // Agregar a letras completadas
                $completedLetters = Cache::get('ou_scraper_completed_letters', []);
                $completedLetters[] = $letter;
                Cache::put('ou_scraper_completed_letters', $completedLetters, now()->addDays(7));
                
                $this->info("✅ Letter '{$letter}' completed successfully");
            } else {
                $this->failedLetters[] = $letter;
                $this->warn("❌ Letter '{$letter}' failed or returned no products");
            }

            // Pausa entre letras para no sobrecargar
            if ($letter !== $letters[array_key_last($letters)]) {
                $this->info("Pausing 10 seconds before next letter...");
                sleep(10);
            }
        }

        // Resumen final
        $this->printSummary($productCount);

        // Si hay letras fallidas, sugerir reintentar
        if (!empty($this->failedLetters)) {
            $this->warn("\nLetters that failed: " . implode(', ', $this->failedLetters));
            $this->info("To retry failed letters: php artisan scrape:ou-robust --letters=" . implode(',', $this->failedLetters));
        }

        return 0;
    }

    private function getLettersToProcess()
    {
        if ($this->option('letters')) {
            return explode(',', $this->option('letters'));
        }

        if ($this->option('resume')) {
            $lastSuccess = Cache::get('ou_scraper_last_success');
            $completedLetters = Cache::get('ou_scraper_completed_letters', []);
            
            if ($lastSuccess) {
                $letters = range('a', 'z');
                $startIndex = array_search($lastSuccess, $letters);
                $remainingLetters = array_slice($letters, $startIndex + 1);
                
                // Filtrar letras ya completadas
                $remainingLetters = array_filter($remainingLetters, function($letter) use ($completedLetters) {
                    return !in_array($letter, $completedLetters);
                });
                
                return array_values($remainingLetters);
            }
            
            // Si no hay última letra exitosa pero hay letras completadas
            if (!empty($completedLetters)) {
                $allLetters = range('a', 'z');
                return array_filter($allLetters, function($letter) use ($completedLetters) {
                    return !in_array($letter, $completedLetters);
                });
            }
        }

        return range('a', 'z');
    }

    private function scrapeLetter($letter, $verify)
    {
        $page = 1;
        $perPage = 50; // Reducido de 100 para mayor estabilidad
        $letterProductCount = 0;
        $consecutiveFailures = 0;
        $maxFailures = 3;

        while ($consecutiveFailures < $maxFailures) {
            $this->info("Scraping page {$page} for letter '{$letter}'...");

            try {
                $products = $this->fetchPage($letter, $page, $perPage, $verify);
                
                if (empty($products)) {
                    $this->info("No more products found for letter '{$letter}'.");
                    break;
                }

                $processedCount = $this->processProducts($products);
                $letterProductCount += $processedCount;
                $consecutiveFailures = 0; // Reset failure counter

                $this->info("✓ Processed {$processedCount} products from page {$page}");

                // Verificar si estamos en la última página
                if ($processedCount < $perPage) {
                    $this->info("Reached end of results for letter '{$letter}'.");
                    break;
                }

                $page++;

                // Pausa progresiva
                $pauseTime = min(8, max(3, $page * 0.7));
                $this->info("Pausing {$pauseTime}s...");
                sleep($pauseTime);

            } catch (\Exception $e) {
                $consecutiveFailures++;
                $this->error("Error on page {$page} (failure {$consecutiveFailures}/{$maxFailures}): " . $e->getMessage());
                
                if ($consecutiveFailures >= $maxFailures) {
                    $this->error("Max failures reached for letter '{$letter}'. Moving to next letter.");
                    break;
                }

                // Espera exponencial antes de reintentar
                $retryDelay = min(30, pow(2, $consecutiveFailures));
                $this->info("Retrying in {$retryDelay} seconds...");
                sleep($retryDelay);
            }
        }

        return $letterProductCount;
    }

    private function fetchPage($letter, $page, $perPage, $verify)
    {
        $response = Http::withHeaders([
            'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36',
            'Connection' => 'close',
            'Accept' => 'application/json',
            'Accept-Encoding' => 'identity', // Sin compresión para evitar error 61
            'Cache-Control' => 'no-cache',
            'Pragma' => 'no-cache'
        ])
        ->withOptions([
            'verify' => $verify,
            'connect_timeout' => 15,
            'read_timeout' => 75,
            'timeout' => $this->timeout,
            'force_ip_resolve' => 'v4',
            'follow_redirects' => true,
            'max_redirects' => 3
        ])
        ->retry(1, 3000, function ($exception, $request) {
            return $exception instanceof \Illuminate\Http\Client\ConnectionException;
        })
        ->get('https://oukosher.org/wp-json/kosher-api/v1/loc/posts', [
            'query' => $letter,
            'limit' => $perPage,
            'page' => $page,
        ]);

        if ($response->failed()) {
            throw new \Exception("API request failed: " . $response->status());
        }

        $data = $response->json();
        return $data['results'] ?? [];
    }

    private function processProducts($products)
    {
        $processed = 0;

        foreach ($products as $product) {
            try {
                $productName = $product['LabelName'] ?? null;
                $brandName = $product['BrandName'] ?? null;
                $symbol = $product['Symbol'] ?? null;

                if (!$productName || !$brandName) {
                    continue;
                }

                // Limpiar datos
                $productName = html_entity_decode(trim($productName, " \t\n\r\0\x0B\"'"));
                $brandName = html_entity_decode(trim($brandName, " \t\n\r\0\x0B\"'"));

                // Determinar status kosher
                $status = 'OU Kosher';
                $symbolLower = strtolower($symbol ?? '');
                if (str_contains($symbolLower, 'd')) {
                    $status = 'OU Dairy';
                } elseif (str_contains($symbolLower, 'pareve') || str_contains($symbolLower, 'p')) {
                    $status = 'OU Pareve';
                } elseif (str_contains($symbolLower, 'fish')) {
                    $status = 'OU Fish';
                }

                // Disparar job inteligente actualizado con matching mejorado
                ProcessOUProductIntelligent::dispatch([
                    'name' => $productName,
                    'brand' => $brandName,
                    'status' => $status
                ])->onQueue('scraping');
                
                Log::info('Dispatched intelligent matching job', [
                    'product_name' => $productName,
                    'brand' => $brandName,
                    'status' => $status,
                    'job_class' => 'ProcessOUProductIntelligent'
                ]);

                $processed++;

            } catch (\Exception $e) {
                Log::error('Error processing individual product', [
                    'product' => $product,
                    'error' => $e->getMessage()
                ]);
                continue;
            }
        }

        return $processed;
    }

    private function printSummary($totalProducts)
    {
        $this->info("\n" . str_repeat("=", 60));
        $this->info("SCRAPE SUMMARY");
        $this->info(str_repeat("=", 60));
        $this->info("Total products processed: {$totalProducts}");
        $this->info("Successful letters: " . count($this->successfulLetters) . "/26");
        $this->info("Failed letters: " . count($this->failedLetters));
        
        if (!empty($this->successfulLetters)) {
            $this->info("✓ Letters completed: " . implode(', ', $this->successfulLetters));
        }
        
        if (!empty($this->failedLetters)) {
            $this->warn("✗ Letters failed: " . implode(', ', $this->failedLetters));
        }
        
        $this->info(str_repeat("=", 60));
    }

    private function showStatus()
    {
        $this->info('📊 OU Scraper Progress Status');
        $this->info(str_repeat("=", 40));

        $lastSuccess = Cache::get('ou_scraper_last_success', 'None');
        $completedLetters = Cache::get('ou_scraper_completed_letters', []);
        
        $this->info("Last successful letter: {$lastSuccess}");
        $this->info("Completed letters (" . count($completedLetters) . "/26): " . implode(', ', $completedLetters));
        
        $remainingLetters = array_diff(range('a', 'z'), $completedLetters);
        $this->info("Remaining letters (" . count($remainingLetters) . "/26): " . implode(', ', $remainingLetters));
        
        $progress = round((count($completedLetters) / 26) * 100, 1);
        $this->info("Progress: {$progress}%");
        
        $this->info(str_repeat("=", 40));
    }

    private function resetProgress()
    {
        $this->warn('⚠️  This will reset all scraping progress!');
        if (!$this->confirm('Are you sure you want to reset all progress?')) {
            $this->info('Reset cancelled.');
            return;
        }

        Cache::forget('ou_scraper_last_success');
        Cache::forget('ou_scraper_completed_letters');
        
        $this->info('✅ Progress reset successfully');
        $this->info('You can now start fresh with: php artisan scrape:ou-robust');
    }
}
