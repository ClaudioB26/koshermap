<?php

namespace App\Services;

use Illuminate\Support\Str;

class ProductTextNormalizer
{
    private static $companySuffixes = [
        'inc', 'inc.', 'llc', 'llc.', 'l.l.c.', 'corp', 'corp.', 'corporation',
        'co', 'co.', 'company', 'ltd', 'ltd.', 'limited', 'sa', 's.a.', 'srl',
        'gmbh', 'ag', 'nv', 'bv', 'pty', 'pty.', 'pty ltd', 'group', 'holdings',
        'international', 'global', 'worldwide', 'enterprises', 'industries'
    ];

    private static $stopWords = [
        // English
        'with', 'and', 'or', 'the', 'a', 'an', 'flavor', 'flavored', 'taste',
        'style', 'brand', 'original', 'classic', 'premium', 'deluxe', 'special',
        'extra', 'plus', 'new', 'improved', 'natural', 'artificial', 'made',
        'fresh', 'frozen', 'dried', 'canned', 'packaged', 'ready', 'instant',
        // Spanish
        'con', 'y', 'o', 'el', 'la', 'los', 'las', 'un', 'una', 'sabor', 'saborizado',
        'estilo', 'marca', 'original', 'clasico', 'premium', 'especial', 'extra',
        'nuevo', 'mejorado', 'natural', 'artificial', 'hecho', 'fresco', 'congelado',
        'seco', 'enlatado', 'empaquetado', 'listo', 'instantaneo'
    ];

    private static $units = [
        'g', 'kg', 'mg', 'oz', 'lb', 'lbs', 'ml', 'l', 'fl oz', 'gal', 'pt', 'qt',
        'gramos', 'kilos', 'miligramos', 'onzas', 'libras', 'mililitros', 'litros',
        'gr', 'kgs', 'mgs'
    ];

    /**
     * Normalización agresiva de texto para matching
     */
    public static function normalize($text, $type = 'product')
    {
        if (empty($text)) {
            return '';
        }

        $text = trim($text);
        
        // 1. Convertir a minúsculas
        $text = strtolower($text);
        
        // 2. Eliminar símbolos especiales
        $text = preg_replace('/[®™©°]/', '', $text);
        
        // 3. Eliminar caracteres no alfanuméricos (excepto espacios y guiones)
        $text = preg_replace('/[^a-z0-9\s\-]/', ' ', $text);
        
        // 4. Eliminar sufijos de empresa (solo para marcas)
        if ($type === 'brand') {
            $text = self::removeCompanySuffixes($text);
        }
        
        // 5. Estandarizar unidades y números
        $text = self::normalizeUnits($text);
        
        // 6. Eliminar stopwords
        $text = self::removeStopWords($text);
        
        // 7. Eliminar espacios múltiples y normalizar
        $text = preg_replace('/\s+/', ' ', $text);
        $text = trim($text);
        
        return $text;
    }

    /**
     * Normalización específica para marcas
     */
    public static function normalizeBrand($brand)
    {
        $normalized = self::normalize($brand, 'brand');
        
        // Mapeos comunes de marcas
        $brandMappings = [
            'mondelez' => 'mondelez international',
            'kraft' => 'kraft heinz',
            'nestle' => 'nestlé',
            'general mills' => 'general mills',
            'pepsico' => 'pepsico',
            'coca cola' => 'coca-cola',
            'coca-cola company' => 'coca-cola',
        ];

        foreach ($brandMappings as $key => $value) {
            if (str_contains($normalized, $key)) {
                return $value;
            }
        }

        return $normalized;
    }

    /**
     * Extraer palabras clave del nombre del producto
     */
    public static function extractKeywords($productName)
    {
        $normalized = self::normalize($productName);
        $words = explode(' ', $normalized);
        
        // Filtrar palabras muy cortas y comunes
        $keywords = array_filter($words, function($word) {
            return strlen($word) > 2 && !in_array($word, self::$stopWords);
        });
        
        return array_values($keywords);
    }

    /**
     * Generar variaciones de búsqueda para el waterfall
     */
    public static function generateSearchVariations($productName, $brandName)
    {
        $normalizedProduct = self::normalize($productName);
        $normalizedBrand = self::normalizeBrand($brandName);
        $productWords = explode(' ', $normalizedProduct);
        
        $variations = [];
        
        // Variación 1: Marca + Nombre completo (alta precisión)
        $variations[] = [
            'query' => $normalizedBrand . ' ' . $normalizedProduct,
            'precision' => 'high'
        ];
        
        // Variación 2: Marca + primeras 2-3 palabras del nombre
        if (count($productWords) >= 2) {
            $partialName = implode(' ', array_slice($productWords, 0, 2));
            $variations[] = [
                'query' => $normalizedBrand . ' ' . $partialName,
                'precision' => 'medium'
            ];
        }
        
        // Variación 3: Solo nombre completo (si la marca es fabricante)
        $variations[] = [
            'query' => $normalizedProduct,
            'precision' => 'low'
        ];
        
        // Variación 4: Palabras clave principales
        $keywords = self::extractKeywords($productName);
        if (count($keywords) >= 2) {
            $keywordQuery = implode(' ', array_slice($keywords, 0, 3));
            $variations[] = [
                'query' => $keywordQuery,
                'precision' => 'fuzzy'
            ];
        }
        
        return $variations;
    }

    /**
     * Eliminar sufijos de empresa
     */
    private static function removeCompanySuffixes($text)
    {
        foreach (self::$companySuffixes as $suffix) {
            $text = preg_replace('/\b' . preg_quote($suffix) . '\b/i', '', $text);
        }
        return $text;
    }

    /**
     * Normalizar unidades y medidas
     */
    private static function normalizeUnits($text)
    {
        // Patrones para eliminar pesos y volúmenes
        $patterns = [
            '/\b\d+\.?\d*\s*(?:' . implode('|', self::$units) . ')\b/i',
            '/\b(?:' . implode('|', self::$units) . ')\s*\d+\.?\d*\b/i',
            '/\b\d+\.?\d*\s*(?:gramos|kilos|litros|mililitros|onzas|libras)\b/i'
        ];
        
        foreach ($patterns as $pattern) {
            $text = preg_replace($pattern, '', $text);
        }
        
        return $text;
    }

    /**
     * Eliminar palabras vacías
     */
    private static function removeStopWords($text)
    {
        $words = explode(' ', $text);
        $filtered = array_filter($words, function($word) {
            return !in_array($word, self::$stopWords);
        });
        
        return implode(' ', $filtered);
    }

    /**
     * Calcular similitud fonética (Metaphone)
     */
    public static function phoneticMatch($str1, $str2)
    {
        $metaphone1 = metaphone($str1);
        $metaphone2 = metaphone($str2);
        
        similar_text($metaphone1, $metaphone2, $percent);
        return $percent;
    }

    /**
     * Calcular similitud Jaro-Winkler (mejor para nombres cortos)
     */
    public static function jaroWinkler($str1, $str2)
    {
        $len1 = strlen($str1);
        $len2 = strlen($str2);
        
        if ($len1 == 0) return $len2 == 0 ? 1 : 0;
        if ($len2 == 0) return 0;
        
        $match_distance = max($len1, $len2) / 2 - 1;
        $str1_matches = array_fill(0, $len1, false);
        $str2_matches = array_fill(0, $len2, false);
        
        $matches = 0;
        $transpositions = 0;
        
        // Find matches
        for ($i = 0; $i < $len1; $i++) {
            $start = max(0, $i - $match_distance);
            $end = min($i + $match_distance + 1, $len2);
            
            for ($j = $start; $j < $end; $j++) {
                if (!$str2_matches[$j] && $str1[$i] == $str2[$j]) {
                    $str1_matches[$i] = true;
                    $str2_matches[$j] = true;
                    $matches++;
                    break;
                }
            }
        }
        
        if ($matches == 0) return 0;
        
        // Count transpositions
        $k = 0;
        for ($i = 0; $i < $len1; $i++) {
            if ($str1_matches[$i]) {
                while (!$str2_matches[$k]) $k++;
                if ($str1[$i] != $str2[$k]) $transpositions++;
                $k++;
            }
        }
        
        // Jaro distance
        $jaro = ($matches / $len1 + $matches / $len2 + ($matches - $transpositions / 2) / $matches) / 3;
        
        // Winkler adjustment
        $prefix = 0;
        for ($i = 0; $i < min(4, $len1, $len2); $i++) {
            if ($str1[$i] == $str2[$i]) $prefix++;
            else break;
        }
        
        return $jaro + $prefix * 0.1 * (1 - $jaro);
    }
}
