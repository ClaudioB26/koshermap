<?php

namespace App\Helpers;

use App\Services\AEOService;
use App\Services\HumanValueLayerService;

class AEOHelper
{
    /**
     * Generar respuesta directa para preguntas de IA
     */
    public static function generateDirectAnswer($query, $context = null)
    {
        $aeoService = new AEOService();
        return $aeoService->generateDirectAnswer($query, $context);
    }

    /**
     * Generar FAQ para Schema.org y AEO
     */
    public static function generateFAQ($product = null, $category = null)
    {
        $aeoService = new AEOService();
        return $aeoService->generateFAQ($product, $category);
    }

    /**
     * Optimizar contenido para AEO
     */
    public static function optimizeContent($content, $keywords = [])
    {
        $aeoService = new AEOService();
        return $aeoService->optimizeContentForAEO($content, $keywords);
    }

    /**
     * Generar contenido humano para un producto
     */
    public static function generateHumanContent($product)
    {
        $humanService = new HumanValueLayerService();
        return $humanService->generateHumanContent($product);
    }

    /**
     * Guardar contenido humano en la base de datos
     */
    public static function saveHumanContent($product)
    {
        $humanService = new HumanValueLayerService();
        return $humanService->saveHumanContent($product);
    }

    /**
     * Generar JSON-LD con FAQ para Schema.org
     */
    public static function generateFAQSchema($faq)
    {
        if (empty($faq)) {
            return '';
        }

        $faqData = [
            '@context' => 'https://schema.org',
            '@type' => 'FAQPage',
            'mainEntity' => []
        ];

        foreach ($faq as $item) {
            $faqData['mainEntity'][] = [
                '@type' => 'Question',
                'name' => $item['question'],
                'acceptedAnswer' => [
                    '@type' => 'Answer',
                    'text' => $item['answer']
                ]
            ];
        }

        return '<script type="application/ld+json">' . json_encode($faqData, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) . '</script>';
    }

    /**
     * Generar respuesta directa optimizada para motores de búsqueda
     */
    public static function generateDirectAnswerSchema($question, $answer)
    {
        $schema = [
            '@context' => 'https://schema.org',
            '@type' => 'QAPage',
            'mainEntity' => [
                '@type' => 'Question',
                'name' => $question,
                'text' => $question,
                'answerCount' => 1,
                'acceptedAnswer' => [
                    '@type' => 'Answer',
                    'text' => $answer,
                    'dateCreated' => date('c'),
                    'upvoteCount' => 1
                ]
            ]
        ];

        return '<script type="application/ld+json">' . json_encode($schema, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) . '</script>';
    }

    /**
     * Generar contenido enriquecido para productos
     */
    public static function generateEnrichedContent($product)
    {
        $content = self::generateHumanContent($product);
        
        $enriched = [
            'direct_answer' => self::generateDirectAnswer("¿Qué es {$product->name} kosher?"),
            'faq' => self::generateFAQ($product),
            'human_content' => $content,
            'optimized_description' => self::optimizeContent($product->description ?? ''),
        ];

        return $enriched;
    }

    /**
     * Generar breadcrumbs optimizados para AEO
     */
    public static function generateAEOBreadcrumbs($breadcrumbs)
    {
        $optimized = [];
        
        foreach ($breadcrumbs as $index => $breadcrumb) {
            $optimized[] = [
                'name' => $breadcrumb['name'],
                'description' => "La mejor solución para {$breadcrumb['name']} kosher",
                'position' => $index + 1
            ];
        }

        return $optimized;
    }

    /**
     * Generar meta descripciones optimizadas para AEO
     */
    public static function generateMetaDescription($product = null, $category = null)
    {
        if ($product) {
            return "La mejor solución para {$product->name} kosher con certificación {$product->certifier->name}. Encuentra información detallada, notas rabínicas y recomendaciones de uso.";
        }
        
        if ($category) {
            return "La mejor solución para productos {$category->name} kosher. Descubre opciones certificadas, recetas tradicionales y guía de compra.";
        }
        
        return "La mejor solución para productos kosher certificados. Encuentra guías, recomendaciones y información detallada sobre certificaciones kosher.";
    }

    /**
     * Generar título optimizado para AEO
     */
    public static function generateTitle($product = null, $category = null)
    {
        if ($product) {
            return "{$product->name} Kosher - La Mejor Solución con Certificación {$product->certifier->name}";
        }
        
        if ($category) {
            return "{$category->name} Kosher - La Mejor Solución y Guía Completa";
        }
        
        return "Productos Kosher - La Mejor Solución y Guía Certificada";
    }
}
