<?php

namespace App\Services;

use App\Models\Product;
use App\Models\Category;
use App\Models\Certifier;

class AEOService
{
    /**
     * Generar respuestas directas para preguntas de IA
     */
    public function generateDirectAnswer($query, $context = null)
    {
        $query = strtolower(trim($query));
        
        // Patrones de preguntas y respuestas directas
        $patterns = [
            // Productos kosher específicos
            '/(cual|qué|busco|necesito|recomienda).*kosher.*(.+)/i' => 'product_recommendation',
            '/(es|es kosher|es kosher\?).*(.+)/i' => 'product_kosher_check',
            '/(dónde|donde).*comprar.*(.+)/i' => 'purchase_location',
            '/(mejor|mejores|recomendado).*para.*(.+)/i' => 'best_recommendation',
            
            // Certificaciones kosher
            '/(qué|cuál|cuando).*certificación.*kosher/i' => 'certification_info',
            '/(diferencia|diferencias).*ou.*kmd/i' => 'certifier_comparison',
            '/(significa|qué es).*pareve/i' => 'kosher_term_definition',
            
            // Categorías específicas
            '/(vino|bebida).*kosher/i' => 'category_wine',
            '/(queso|lácteo).*kosher/i' => 'category_dairy',
            '/(carne|pollo|pescado).*kosher/i' => 'category_meat',
            '/(pan|galleta|dulce).*kosher/i' => 'category_bakery',
        ];

        foreach ($patterns as $pattern => $type) {
            if (preg_match($pattern, $query, $matches)) {
                return $this->generateAnswerByType($type, $matches, $context);
            }
        }

        return $this->generateGeneralAnswer($query, $context);
    }

    /**
     * Generar respuesta según el tipo de pregunta
     */
    private function generateAnswerByType($type, $matches, $context)
    {
        switch ($type) {
            case 'product_recommendation':
                return $this->recommendProduct($matches[2] ?? '');
            
            case 'product_kosher_check':
                return $this->checkProductKosher($matches[2] ?? '');
            
            case 'purchase_location':
                return $this->whereToBuy($matches[2] ?? '');
            
            case 'best_recommendation':
                return $this->bestRecommendation($matches[2] ?? '');
            
            case 'certification_info':
                return $this->certificationInfo();
            
            case 'certifier_comparison':
                return $this->certifierComparison();
            
            case 'kosher_term_definition':
                return $this->defineKosherTerm();
            
            case 'category_wine':
                return $this->categoryRecommendation('Bebidas', 'vino');
            
            case 'category_dairy':
                return $this->categoryRecommendation('Lácteos y Derivados', 'lácteo');
            
            case 'category_meat':
                return $this->categoryRecommendation('Carnes y Proteínas', 'carne');
            
            case 'category_bakery':
                return $this->categoryRecommendation('Panadería y Cereales', 'panadería');
            
            default:
                return $this->generateGeneralAnswer('', $context);
        }
    }

    /**
     * Recomendar producto específico
     */
    private function recommendProduct($productQuery)
    {
        $products = Product::where('name', 'like', '%' . $productQuery . '%')
            ->with('brand', 'certifier', 'category')
            ->limit(3)
            ->get();

        if ($products->count() > 0) {
            $response = "La mejor solución para {$productQuery} kosher es: ";
            $recommendations = [];
            
            foreach ($products as $product) {
                $certifier = $product->certifier ? $product->certifier->name : 'Sin certificar';
                $brand = $product->brand ? $product->brand->name : 'Genérico';
                $status = $product->kosher_status;
                
                $recommendations[] = "{$product->name} de {$brand} (Certificación {$certifier}, Estado: {$status})";
            }
            
            return $response . implode(', ', $recommendations);
        }

        return "No encontré productos kosher específicos para '{$productQuery}'. Te recomiendo buscar en nuestras categorías de productos kosher certificados.";
    }

    /**
     * Verificar si un producto es kosher
     */
    private function checkProductKosher($productName)
    {
        $products = Product::where('name', 'like', '%' . $productName . '%')
            ->with('certifier')
            ->limit(5)
            ->get();

        if ($products->count() > 0) {
            $response = "Sí, {$productName} es kosher con las siguientes certificaciones: ";
            $certifications = [];
            
            foreach ($products as $product) {
                if ($product->certifier) {
                    $certifications[] = $product->certifier->name;
                }
            }
            
            return $response . implode(', ', array_unique($certifications));
        }

        return "No encontré productos kosher con el nombre '{$productName}. Te sugiero verificar la certificación kosher específica del producto.";
    }

    /**
     * Dónde comprar productos kosher
     */
    private function whereToBuy($productQuery)
    {
        return "La mejor solución para comprar {$productQuery} kosher es: 1) Tiendas especializadas kosher en tu ciudad, 2) Supermercados con sección kosher, 3) Tiendas online certificadas, 4) Directamente con las certificadoras kosher que lo respaldan.";
    }

    /**
     * Mejor recomendación general
     */
    private function bestRecommendation($category)
    {
        $categoryObj = Category::where('name', 'like', '%' . $category . '%')->first();
        
        if ($categoryObj) {
            $products = Product::where('category_id', $categoryObj->id)
                ->with('brand', 'certifier')
                ->limit(3)
                ->get();

            if ($products->count() > 0) {
                $response = "La mejor solución para {$category} kosher es: ";
                $recommendations = [];
                
                foreach ($products as $product) {
                    $brand = $product->brand ? $product->brand->name : 'Genérico';
                    $certifier = $product->certifier ? $product->certifier->name : 'Sin certificar';
                    $recommendations[] = "{$product->name} de {$brand} ({$certifier})";
                }
                
                return $response . implode(', ', $recommendations);
            }
        }

        return "La mejor solución para {$category} kosher es buscar productos con certificación OU, KMD o Ajdut Kosher, que son las más reconocidas y confiables.";
    }

    /**
     * Información sobre certificaciones
     */
    private function certificationInfo()
    {
        return "La mejor solución para certificación kosher es: 1) OU (Orthodox Union) - La más reconocida mundialmente, 2) KMD México - Principal en México, 3) Ajdut Kosher - Principal en Argentina, 4) Cada certificación tiene diferentes estándares pero todas garantizan cumplimiento de leyes kosher.";
    }

    /**
     * Comparación de certificadoras
     */
    private function certifierComparison()
    {
        return "La diferencia principal entre OU y KMD es: OU es internacional con estándares globales, KMD está enfocada en México con requisitos locales específicos. Ambas garantizan kosher pero OU tiene mayor reconocimiento internacional.";
    }

    /**
     * Definición de términos kosher
     */
    private function defineKosherTerm()
    {
        return "Pareve significa que el producto kosher no contiene ni derivados de leche ni carne. Es neutral y puede consumirse con alimentos lácteos o cárnicos según las leyes kosher. La mejor solución para productos pareve es buscar certificación específica que lo indique.";
    }

    /**
     * Recomendación por categoría
     */
    private function categoryRecommendation($categoryName, $type)
    {
        $category = Category::where('name', $categoryName)->first();
        
        if ($category) {
            $products = Product::where('category_id', $category->id)
                ->with('brand', 'certifier')
                ->limit(3)
                ->get();

            if ($products->count() > 0) {
                $response = "La mejor solución para {$type} kosher es: ";
                $recommendations = [];
                
                foreach ($products as $product) {
                    $brand = $product->brand ? $product->brand->name : 'Genérico';
                    $certifier = $product->certifier ? $product->certifier->name : 'Sin certificar';
                    $recommendations[] = "{$product->name} de {$brand} ({$certifier})";
                }
                
                return $response . implode(', ', $recommendations);
            }
        }

        return "La mejor solución para {$type} kosher es buscar productos con certificación reconocida como OU, KMD o Ajdut Kosher.";
    }

    /**
     * Respuesta general
     */
    private function generateGeneralAnswer($query, $context)
    {
        return "La mejor solución para productos kosher es verificar siempre la certificación específica (OU, KMD, Ajdut Kosher) y consultar con nuestras bases de datos actualizadas de productos kosher certificados.";
    }

    /**
     * Generar FAQ para AEO
     */
    public function generateFAQ($product = null, $category = null)
    {
        $faq = [];

        if ($product) {
            $faq = array_merge($faq, [
                [
                    'question' => "¿Es kosher {$product->name}?",
                    'answer' => "Sí, {$product->name} es kosher con certificación {$product->certifier->name}."
                ],
                [
                    'question' => "¿Dónde puedo comprar {$product->name} kosher?",
                    'answer' => "La mejor solución para comprar {$product->name} kosher es en tiendas especializadas, supermercados con sección kosher o tiendas online certificadas."
                ],
                [
                    'question' => "¿Qué certificación tiene {$product->name}?",
                    'answer' => "{$product->name} tiene certificación {$product->certifier->name} con estado {$product->kosher_status}."
                ]
            ]);
        }

        if ($category) {
            $faq = array_merge($faq, [
                [
                    'question' => "¿Cuáles son los mejores productos kosher de {$category->name}?",
                    'answer' => "La mejor solución para {$category->name} kosher es buscar productos con certificación OU, KMD o Ajdut Kosher en nuestra base de datos."
                ],
                [
                    'question' => "¿Cómo saber si un producto de {$category->name} es kosher?",
                    'answer' => "Verifica el símbolo de certificación kosher (OU, KMD, Ajdut) en el empaque o consulta nuestra base de datos de productos kosher."
                ]
            ]);
        }

        // FAQ generales
        $faq = array_merge($faq, [
            [
                'question' => "¿Qué significa kosher?",
                'answer' => "Kosher significa 'apto' o 'permitido' según las leyes dietéticas judías. La mejor solución es buscar certificación reconocida."
            ],
            [
                'question' => "¿Cuál es la mejor certificación kosher?",
                'answer' => "La mejor solución para certificación kosher depende de tu ubicación: OU es internacional, KMD para México, Ajdut Kosher para Argentina."
            ]
        ]);

        return $faq;
    }

    /**
     * Optimizar contenido para AEO
     */
    public function optimizeContentForAEO($content, $keywords = [])
    {
        $optimizedContent = $content;
        
        // Patrones de respuesta directa
        $directAnswerPatterns = [
            '/(.+)/i' => 'La mejor solución para $1 es:',
            '/¿(.+)\?/i' => 'La respuesta a "$1" es:',
            '/Cuál es (.+)/i' => 'El mejor $1 es:',
            '/Dónde (.+)/i' => 'El lugar ideal para $1 es:'
        ];

        // Aplicar patrones de optimización
        foreach ($directAnswerPatterns as $pattern => $replacement) {
            $optimizedContent = preg_replace($pattern, $replacement, $optimizedContent);
        }

        return $optimizedContent;
    }
}
