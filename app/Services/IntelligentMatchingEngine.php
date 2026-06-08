<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use App\Models\OuOffMapping;
use App\Models\FailedMatch;

class IntelligentMatchingEngine
{
    private const CONFIDENCE_THRESHOLDS = [
        'auto_match' => 80,
        'manual_review' => 50,
        'reject' => 0
    ];

    private const SCORING_WEIGHTS = [
        'brand_exact_match' => 40,
        'name_similarity' => 30,
        'category_match' => 20,
        'country_match' => 10,
        'barcode_bonus' => 15,
        'image_bonus' => 5
    ];

    /**
     * Proceso principal de matching inteligente
     */
    public function matchProduct($ouProductName, $ouBrandName, $ouCategory = null, $ouCountry = 'US')
    {
        Log::info('Starting intelligent matching', [
            'ou_product' => $ouProductName,
            'ou_brand' => $ouBrandName
        ]);

        // 1. Verificar si ya tenemos un mapeo existente
        $existingMapping = $this->findExistingMapping($ouProductName, $ouBrandName);
        if ($existingMapping) {
            Log::info('Found existing mapping', ['confidence' => $existingMapping->confidence_score]);
            return $this->formatResult($existingMapping, 'existing');
        }

        // 2. Ejecutar búsqueda en cascada
        $candidates = $this->cascadeSearch($ouProductName, $ouBrandName);
        
        if (empty($candidates)) {
            return $this->handleNoMatches($ouProductName, $ouBrandName);
        }

        // 3. Calcular puntuación de confianza para cada candidato
        $scoredCandidates = [];
        foreach ($candidates as $candidate) {
            $score = $this->calculateConfidenceScore(
                $ouProductName, 
                $ouBrandName, 
                $candidate, 
                $ouCategory, 
                $ouCountry
            );
            
            if ($score['total'] >= self::CONFIDENCE_THRESHOLDS['manual_review']) {
                $scoredCandidates[] = array_merge($candidate, ['confidence_score' => $score]);
            }
        }

        // 4. Ordenar por puntuación y seleccionar el mejor
        usort($scoredCandidates, function($a, $b) {
            return $b['confidence_score']['total'] - $a['confidence_score']['total'];
        });

        if (empty($scoredCandidates)) {
            return $this->handleNoMatches($ouProductName, $ouBrandName, $candidates);
        }

        $bestMatch = $scoredCandidates[0];
        $confidence = $bestMatch['confidence_score']['total'];

        // 5. Determinar acción basada en confianza
        if ($confidence >= self::CONFIDENCE_THRESHOLDS['auto_match']) {
            return $this->createAutoMapping($ouProductName, $ouBrandName, $bestMatch);
        } else {
            return $this->createPendingReview($ouProductName, $ouBrandName, $bestMatch, $scoredCandidates);
        }
    }

    /**
     * Búsqueda en cascada con múltiples estrategias
     */
    private function cascadeSearch($productName, $brandName)
    {
        $variations = ProductTextNormalizer::generateSearchVariations($productName, $brandName);
        $allCandidates = [];
        $seenBarcodes = [];

        foreach ($variations as $variation) {
            Log::info('Trying search variation', [
                'query' => $variation['query'],
                'precision' => $variation['precision']
            ]);

            $candidates = $this->searchOpenFoodFacts($variation['query'], $variation['precision']);
            
            // Eliminar duplicados por barcode
            foreach ($candidates as $candidate) {
                $barcode = $candidate['code'] ?? null;
                if ($barcode && !isset($seenBarcodes[$barcode])) {
                    $seenBarcodes[$barcode] = true;
                    $allCandidates[] = $candidate;
                }
            }

            // Si encontramos candidatos de alta precisión, podemos parar
            if ($variation['precision'] === 'high' && count($candidates) > 0) {
                break;
            }
        }

        return array_slice($allCandidates, 0, 10); // Limitar a top 10
    }

    /**
     * Búsqueda en Open Food Facts con diferentes niveles de precisión y reintentos
     */
    private function searchOpenFoodFacts($searchTerm, $precision = 'medium')
    {
        // Rate limiting: esperar entre requests
        $this->rateLimitRequest();
        
        $params = [
            'search_terms' => $searchTerm,
            'search_simple' => 1,
            'action' => 'process',
            'json' => 1,
            'page_size' => $precision === 'high' ? 5 : 20
        ];

        // Ajustar parámetros según precisión
        if ($precision === 'high') {
            $params['tagtype_0'] = 'brands';
            $params['tag_contains_0'] = 'contains';
        }

        // User-Agent con credenciales de usuario registrado en OFF
        $userAgent = 'KosherStatus/1.0 (User: claudiob; claudiobprogramador@gmail.com)';
        
        // Token de autenticación para OFF
        $authToken = base64_encode('claudiob:H7.JmdD@XKh!2um');

        // Sistema de reintentos
        $maxRetries = 3;
        $retryCount = 0;
        
        while ($retryCount < $maxRetries) {
            try {
                $response = Http::timeout(20)
                    ->withOptions([
                        'verify' => filter_var(env('HTTP_VERIFY_SSL', true), FILTER_VALIDATE_BOOLEAN),
                        'connect_timeout' => 10,
                        'read_timeout' => 15
                    ])
                    ->withHeaders([
                        'User-Agent' => $userAgent,
                        'Accept' => 'application/json',
                        'Accept-Language' => 'en-US,en;q=0.9',
                        'Accept-Encoding' => 'gzip, deflate',
                        'Connection' => 'keep-alive',
                        'Cache-Control' => 'max-age=0',
                        'Authorization' => 'Basic ' . $authToken
                    ])
                    ->get('https://world.openfoodfacts.org/cgi/search.pl', $params);

                if ($response->successful()) {
                    $products = $response->json()['products'] ?? [];
                    Log::info('OFF Search results', [
                        'term' => $searchTerm, 
                        'count' => count($products),
                        'retry_count' => $retryCount
                    ]);
                    return $products;
                }

                $statusCode = $response->status();
                
                if ($statusCode === 429) {
                    Log::warning('OFF rate limit exceeded, retrying...', [
                        'term' => $searchTerm, 
                        'retry' => $retryCount + 1,
                        'status' => $statusCode
                    ]);
                    $retryCount++;
                    
                    if ($retryCount < $maxRetries) {
                        // Esperar exponencial: 2s, 4s, 8s
                        $waitTime = 2 ** $retryCount;
                        Log::info("Waiting {$waitTime} seconds before retry...");
                        sleep($waitTime);
                        continue;
                    }
                }
                
                if ($statusCode === 403) {
                    Log::error('OFF access forbidden - possible blocking', [
                        'term' => $searchTerm, 
                        'status' => $statusCode,
                        'retry_count' => $retryCount
                    ]);
                    return [];
                }
                
                // Otro error
                Log::error('OFF API request failed', [
                    'term' => $searchTerm, 
                    'precision' => $precision,
                    'status' => $statusCode,
                    'retry_count' => $retryCount,
                    'response' => substr($response->body(), 0, 500)
                ]);
                
                $retryCount++;
                if ($retryCount < $maxRetries) {
                    sleep(1); // Esperar 1 segundo antes de reintentar
                }
                
            } catch (\Exception $e) {
                Log::error('OFF API request exception', [
                    'term' => $searchTerm,
                    'error' => $e->getMessage(),
                    'retry_count' => $retryCount
                ]);
                
                $retryCount++;
                if ($retryCount < $maxRetries) {
                    sleep(1);
                }
            }
        }
        
        Log::error('OFF API failed after all retries', [
            'term' => $searchTerm,
            'total_retries' => $maxRetries
        ]);
        
        return [];
    }
    
    /**
     * Rate limiting para evitar bloqueos según política de OFF
     */
    private function rateLimitRequest()
    {
        static $lastRequest = 0;
        $minInterval = 1500; // 1.5 segundos entre requests (más conservador)
        
        $now = microtime(true) * 1000; // Convertir a milisegundos
        $timeSinceLastRequest = $now - $lastRequest;
        
        if ($timeSinceLastRequest < $minInterval) {
            $sleepTime = ($minInterval - $timeSinceLastRequest) / 1000; // Convertir a segundos
            usleep($sleepTime * 1000000); // Dormir en microsegundos
        }
        
        $lastRequest = microtime(true) * 1000;
    }

    /**
     * Calcular puntuación de confianza detallada
     */
    private function calculateConfidenceScore($ouName, $ouBrand, $offProduct, $ouCategory = null, $ouCountry = 'US')
    {
        $score = [
            'brand_exact_match' => 0,
            'name_similarity' => 0,
            'category_match' => 0,
            'country_match' => 0,
            'barcode_bonus' => 0,
            'image_bonus' => 0,
            'total' => 0,
            'breakdown' => []
        ];

        // 1. Match de marca (40 puntos)
        $offBrand = $offProduct['brands'] ?? '';
        $brandScore = $this->calculateBrandMatch($ouBrand, $offBrand);
        $score['brand_exact_match'] = $brandScore;
        $score['breakdown'][] = "Brand match: {$brandScore}/40";

        // 2. Similitud de nombre (30 puntos)
        $offName = $offProduct['product_name'] ?? '';
        $nameScore = $this->calculateNameSimilarity($ouName, $offName);
        $score['name_similarity'] = $nameScore;
        $score['breakdown'][] = "Name similarity: {$nameScore}/30";

        // 3. Coincidencia de categoría (20 puntos)
        $categoryScore = $this->calculateCategoryMatch($ouCategory, $offProduct);
        $score['category_match'] = $categoryScore;
        $score['breakdown'][] = "Category match: {$categoryScore}/20";

        // 4. Coincidencia de país (10 puntos)
        $countryScore = $this->calculateCountryMatch($ouCountry, $offProduct);
        $score['country_match'] = $countryScore;
        $score['breakdown'][] = "Country match: {$countryScore}/10";

        // 5. Bonus por barcode (15 puntos extra)
        if (!empty($offProduct['code'])) {
            $score['barcode_bonus'] = 15;
            $score['breakdown'][] = "Barcode bonus: 15/15";
        }

        // 6. Bonus por imagen (5 puntos extra)
        if (!empty($offProduct['image_url'])) {
            $score['image_bonus'] = 5;
            $score['breakdown'][] = "Image bonus: 5/5";
        }

        $score['total'] = array_sum([
            $score['brand_exact_match'],
            $score['name_similarity'],
            $score['category_match'],
            $score['country_match'],
            $score['barcode_bonus'],
            $score['image_bonus']
        ]);

        return $score;
    }

    /**
     * Calcular match de marca con múltiples algoritmos
     */
    private function calculateBrandMatch($ouBrand, $offBrand)
    {
        if (empty($offBrand)) return 0;

        $normalizedOU = ProductTextNormalizer::normalizeBrand($ouBrand);
        $normalizedOFF = ProductTextNormalizer::normalizeBrand($offBrand);

        // Match exacto: 40 puntos
        if ($normalizedOU === $normalizedOFF) {
            return 40;
        }

        // Contiene: 35 puntos
        if (str_contains($normalizedOFF, $normalizedOU) || str_contains($normalizedOU, $normalizedOFF)) {
            return 35;
        }

        // Similitud fonética: 25-30 puntos
        $phoneticScore = ProductTextNormalizer::phoneticMatch($normalizedOU, $normalizedOFF);
        if ($phoneticScore > 80) {
            return 30;
        } elseif ($phoneticScore > 60) {
            return 25;
        }

        // Jaro-Winkler: 15-25 puntos
        $jaroScore = ProductTextNormalizer::jaroWinkler($normalizedOU, $normalizedOFF) * 100;
        if ($jaroScore > 85) {
            return 25;
        } elseif ($jaroScore > 70) {
            return 20;
        } elseif ($jaroScore > 50) {
            return 15;
        }

        return 0;
    }

    /**
     * Calcular similitud de nombres
     */
    private function calculateNameSimilarity($ouName, $offName)
    {
        if (empty($offName)) return 0;

        $normalizedOU = ProductTextNormalizer::normalize($ouName);
        $normalizedOFF = ProductTextNormalizer::normalize($offName);

        // Match exacto: 30 puntos
        if ($normalizedOU === $normalizedOFF) {
            return 30;
        }

        // Levenshtein distance: 20-25 puntos
        $distance = levenshtein($normalizedOU, $normalizedOFF);
        $maxLength = max(strlen($normalizedOU), strlen($normalizedOFF));
        $similarity = ($maxLength - $distance) / $maxLength * 100;

        if ($similarity > 90) {
            return 25;
        } elseif ($similarity > 80) {
            return 22;
        } elseif ($similarity > 70) {
            return 20;
        } elseif ($similarity > 60) {
            return 18;
        } elseif ($similarity > 50) {
            return 15;
        }

        // Palabras clave coincidentes: 10-15 puntos
        $ouKeywords = ProductTextNormalizer::extractKeywords($ouName);
        $offKeywords = ProductTextNormalizer::extractKeywords($offName);
        
        $commonKeywords = array_intersect($ouKeywords, $offKeywords);
        $keywordMatch = count($commonKeywords) / max(count($ouKeywords), 1) * 15;

        return min($keywordMatch, 15);
    }

    /**
     * Calcular coincidencia de categoría
     */
    private function calculateCategoryMatch($ouCategory, $offProduct)
    {
        if (empty($ouCategory) || empty($offProduct['categories_tags'])) {
            return 0;
        }

        $categories = $offProduct['categories_tags'];
        $normalizedOU = strtolower(ProductTextNormalizer::normalize($ouCategory));

        foreach ($categories as $category) {
            if (str_starts_with($category, 'en:')) {
                $offCategory = substr($category, 3);
                $normalizedOFF = strtolower(str_replace('-', ' ', $offCategory));

                if (str_contains($normalizedOFF, $normalizedOU) || str_contains($normalizedOU, $normalizedOFF)) {
                    return 20;
                }
            }
        }

        return 0;
    }

    /**
     * Calcular coincidencia de país
     */
    private function calculateCountryMatch($ouCountry, $offProduct)
    {
        if (empty($offProduct['countries_tags'])) {
            return 5; // Puntos por defecto si no hay info
        }

        $countries = $offProduct['countries_tags'];
        $normalizedOU = strtolower($ouCountry);

        foreach ($countries as $country) {
            if (str_starts_with($country, 'en:')) {
                $offCountry = substr($country, 3);
                $normalizedOFF = strtolower(str_replace('-', ' ', $offCountry));

                if ($normalizedOFF === $normalizedOU || str_contains($normalizedOFF, $normalizedOU)) {
                    return 10;
                }
            }
        }

        return 0;
    }

    /**
     * Buscar mapeos existentes
     */
    private function findExistingMapping($productName, $brandName)
    {
        return OuOffMapping::where('ou_product_name', $productName)
            ->where('ou_brand_name', $brandName)
            ->where('match_status', '!=', 'rejected')
            ->first();
    }

    /**
     * Manejar casos sin matches
     */
    private function handleNoMatches($productName, $brandName, $candidates = [])
    {
        // Guardar en failed_matches para revisión humana
        FailedMatch::create([
            'ou_product_name' => $productName,
            'ou_brand_name' => $brandName,
            'search_term_used' => ProductTextNormalizer::normalize($productName . ' ' . $brandName),
            'off_candidates' => array_slice($candidates, 0, 5),
            'best_score' => 0,
            'rejection_reason' => 'No suitable matches found',
            'needs_human_review' => true
        ]);

        return [
            'status' => 'no_match',
            'message' => 'No matches found. Saved for human review.',
            'confidence_score' => 0
        ];
    }

    /**
     * Crear mapeo automático
     */
    private function createAutoMapping($ouName, $ouBrand, $offProduct)
    {
        $mapping = OuOffMapping::create([
            'ou_product_name' => $ouName,
            'ou_brand_name' => $ouBrand,
            'off_product_name' => $offProduct['product_name'] ?? null,
            'off_brand_name' => $offProduct['brands'] ?? null,
            'off_barcode' => $offProduct['code'] ?? null,
            'off_image_url' => $offProduct['image_url'] ?? null,
            'confidence_score' => $offProduct['confidence_score']['total'],
            'match_status' => 'auto_matched',
            'scoring_breakdown' => $offProduct['confidence_score'],
            'matched_by' => 'system'
        ]);

        Log::info('Created automatic mapping', ['confidence' => $mapping->confidence_score]);

        return $this->formatResult($mapping, 'auto_matched');
    }

    /**
     * Crear mapeo pendiente de revisión
     */
    private function createPendingReview($ouName, $ouBrand, $bestMatch, $allCandidates)
    {
        FailedMatch::create([
            'ou_product_name' => $ouName,
            'ou_brand_name' => $ouBrand,
            'search_term_used' => ProductTextNormalizer::normalize($ouName . ' ' . $ouBrand),
            'off_candidates' => $allCandidates,
            'best_score' => $bestMatch['confidence_score']['total'],
            'rejection_reason' => 'Low confidence score',
            'needs_human_review' => true
        ]);

        return [
            'status' => 'pending_review',
            'message' => 'Requires human review',
            'confidence_score' => $bestMatch['confidence_score']['total'],
            'best_candidate' => $bestMatch,
            'off_barcode' => $bestMatch['code'] ?? null,  // 🔧 AGREGADO: Extraer barcode del best_candidate
            'off_image_url' => $bestMatch['image_url'] ?? null,  // 🔧 AGREGADO: Extraer image_url
            'off_product_name' => $bestMatch['product_name'] ?? null,  // 🔧 AGREGADO: Extraer product_name
            'off_brand_name' => $bestMatch['brands'] ?? null  // 🔧 AGREGADO: Extraer brands
        ];
    }

    /**
     * Formatear resultado
     */
    private function formatResult($mapping, $type)
    {
        return [
            'status' => $type,
            'off_barcode' => $mapping->off_barcode,
            'off_image_url' => $mapping->off_image_url,
            'off_product_name' => $mapping->off_product_name,
            'confidence_score' => $mapping->confidence_score,
            'match_status' => $mapping->match_status,
            'scoring_breakdown' => $mapping->scoring_breakdown
        ];
    }
}
