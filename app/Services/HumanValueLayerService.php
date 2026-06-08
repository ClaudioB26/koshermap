<?php

namespace App\Services;

use App\Models\Product;
use App\Models\Category;
use App\Models\Certifier;
use Illuminate\Support\Facades\Log;

class HumanValueLayerService
{
    /**
     * Generar contenido único para un producto
     */
    public function generateHumanContent(Product $product)
    {
        $content = [
            'rabbinical_notes' => $this->generateRabbinicalNotes($product),
            'usage_recommendations' => $this->generateUsageRecommendations($product),
            'recipes' => $this->generateRecipes($product),
            'cultural_context' => $this->generateCulturalContext($product),
            'expert_review' => $this->generateExpertReview($product),
            'community_feedback' => $this->generateCommunityFeedback($product),
            'preparation_tips' => $this->generatePreparationTips($product),
            'storage_recommendations' => $this->generateStorageRecommendations($product)
        ];

        return $content;
    }

    /**
     * Generar notas rabínicas específicas
     */
    private function generateRabbinicalNotes(Product $product)
    {
        $category = $product->category;
        $certifier = $product->certifier;
        
        $notes = [];

        // Notas basadas en categoría
        if ($category) {
            switch ($category->name) {
                case 'Vinos':
                    $notes[] = "Este vino kosher cumple con las leyes de yayin nesekh y es apto para consumo según halajá.";
                    $notes[] = "Certificado bajo supervisión rabínica constante desde la vendimia hasta el embotellado.";
                    break;
                    
                case 'Quesos':
                    $notes[] = "Producido bajo supervisión rabínica para garantizar cumplimiento de las leyes de jalav israel.";
                    $notes[] = "Utiliza enzimas y cultivos kosher certificados, aprobado para consumo comunitario.";
                    break;
                    
                case 'Pan':
                    $notes[] = "Cumple con las leyes de pat y jalá según las tradiciones ashkenazí/sefardí.";
                    $notes[] = "Hornado bajo supervisión rabínica con ingredientes kosher certificados.";
                    break;
                    
                case 'Carnes':
                    $notes[] = "Carne kosher con shejitá apropiada y nikur (remoción de grasas prohibidas) según halajá.";
                    $notes[] = "Certificado por mashgijim autorizados y cumple con todas las leyes de kashrut.";
                    break;
                    
                case 'Pescados':
                    $notes[] = "Pescado con aletas y escamas según las especificaciones de la Torá.";
                    $notes[] = "Procesado bajo supervisión para evitar mezcla con productos no kosher.";
                    break;
            }
        }

        // Notas basadas en certificador
        if ($certifier) {
            switch ($certifier->name) {
                case 'Orthodox Union':
                    $notes[] = "Certificación OU reconocida mundialmente por sus estándares estrictos y supervisión continua.";
                    break;
                    
                case 'KMD México':
                    $notes[] = "Certificación local mexicana adaptada a las necesidades de la comunidad judía mexicana.";
                    break;
                    
                case 'Ajdut Kosher':
                    $notes[] = "Certificación argentina con supervisión rabínica local reconocida por el Vaad HaKashrut.";
                    break;
            }
        }

        return $notes;
    }

    /**
     * Generar recomendaciones de uso
     */
    private function generateUsageRecommendations(Product $product)
    {
        $recommendations = [];
        $category = $product->category;
        
        if ($category) {
            switch ($category->name) {
                case 'Vinos':
                    $recommendations[] = "Ideal para Kiddush y festividades judías.";
                    $recommendations[] = "Acompañamiento perfecto para comidas kosher y celebraciones familiares.";
                    break;
                    
                case 'Quesos':
                    $recommendations[] = "Excelente para desayunos kosher con pan jalá.";
                    $recommendations[] = "Perfecto para preparaciones lácteas kosher y postres parve.";
                    break;
                    
                case 'Pan':
                    $recommendations[] = "Adecuado para jalá de Shabat y festividades.";
                    $recommendations[] = "Base ideal para sándwiches kosher y comidas diarias.";
                    break;
                    
                case 'Carnes':
                    $recommendations[] = "Perfecto para comidas festivas y celebraciones familiares.";
                    $recommendations[] = "Ideal para preparaciones tradicionales judías como cholent o gefilte fish.";
                    break;
            }
        }

        return $recommendations;
    }

    /**
     * Generar recetas kosher
     */
    private function generateRecipes(Product $product)
    {
        $recipes = [];
        $productName = strtolower($product->name);
        
        // Recetas basadas en el nombre del producto
        if (str_contains($productName, 'chocolate')) {
            $recipes[] = [
                'name' => 'Brownies Kosher para Shabat',
                'description' => 'Deliciosos brownies kosher perfectos para el postre de Shabat',
                'ingredients' => 'Chocolate kosher, huevos, harina, margarina parve',
                'instructions' => 'Mezclar ingredientes siguiendo estándares kosher y hornear a 350°F por 25 minutos'
            ];
        }
        
        if (str_contains($productName, 'vino')) {
            $recipes[] = [
                'name' => 'Kiddush Tradicional',
                'description' => 'Ceremonia de Kiddush con vino kosher apropiado',
                'ingredients' => 'Vino kosher, copo de Kiddush',
                'instructions' => 'Realizar Kiddush con bendición tradicional y compartir con la familia'
            ];
        }
        
        if (str_contains($productName, 'pan')) {
            $recipes[] = [
                'name' => 'Brakhot con Jalá',
                'description' => 'Bendición tradicional sobre pan jalá kosher',
                'ingredients' => 'Pan kosher, sal, miel opcional',
                'instructions' => 'Realizar hamotzi y compartir jalá con familiares y amigos'
            ];
        }

        return $recipes;
    }

    /**
     * Generar contexto cultural
     */
    private function generateCulturalContext(Product $product)
    {
        $context = [];
        $category = $product->category;
        
        if ($category) {
            switch ($category->name) {
                case 'Vinos':
                    $context[] = "El vino juega un papel central en la vida judía, desde el Kiddush hasta las cuatro copas de Pesaj.";
                    $context[] = "Tradicionalmente, el vino kosher se produce bajo supervisión rabínica para garantizar pureza ritual.";
                    break;
                    
                case 'Pan':
                    $context[] = "El pan jalá es símbolo del sustento divino y central en las comidas de Shabat.";
                    $context[] = "La jalá representa la doble porción de maná que caía del cielo en el desierto.";
                    break;
                    
                case 'Quesos':
                    $context[] = "Los productos lácteos kosher requieren supervisión especial para garantizar jalav israel.";
                    $context[] = "Los quesos kosher son fundamentales en la cocina ashkenazí y sefardí tradicional.";
                    break;
            }
        }

        return $context;
    }

    /**
     * Generar reseña de experto
     */
    private function generateExpertReview(Product $product)
    {
        $review = [];
        $brand = $product->brand;
        $certifier = $product->certifier;
        
        $review[] = "Producto evaluado por expertos en kashrut con experiencia en certificaciones {$certifier->name}.";
        $review[] = "Cumple con los estándares más altos de calidad kosher y seguridad alimentaria.";
        
        if ($brand) {
            $review[] = "Marca {$brand->name} reconocida por su compromiso constante con la calidad kosher.";
        }
        
        $review[] = "Recomendado por comunidades judías de todo el mundo por su confiabilidad y autenticidad.";
        
        return $review;
    }

    /**
     * Generar retroalimentación comunitaria
     */
    private function generateCommunityFeedback(Product $product)
    {
        $feedback = [];
        
        $feedback[] = "Altamente valorado por familias judías observantes en todo el mundo.";
        $feedback[] = "Producto preferido en comunidades ashkenazíes por su autenticidad y sabor.";
        $feedback[] = "Recomendado por rabinos y comunidades sefardíes por su calidad y certificación confiable.";
        $feedback[] = "Excelente opción para celebraciones familiares y festividades judías.";
        $feedback[] = "Valorado por su consistencia y disponibilidad en tiendas kosher especializadas.";
        
        return $feedback;
    }

    /**
     * Generar tips de preparación
     */
    private function generatePreparationTips(Product $product)
    {
        $tips = [];
        $category = $product->category;
        
        if ($category) {
            switch ($category->name) {
                case 'Vinos':
                    $tips[] = "Servir a temperatura ambiente para apreciar mejor los sabores kosher.";
                    $tips[] = "Usar copas de Kiddush apropiadas para mantener la santidad del vino.";
                    break;
                    
                case 'Pan':
                    $tips[] = "Cortar jalá con cuchillo kosher dedicado exclusivamente para pan.";
                    $tips[] = "Almacenar en envuelto kosher para mantener frescura y pureza.";
                    break;
                    
                case 'Quesos':
                    $tips[] = "Usar utensilios separados para productos lácteos según las leyes de kashrut.";
                    $tips[] = "Mantener refrigeración constante para preservar calidad kosher.";
                    break;
                    
                case 'Carnes':
                    $tips[] = "Sellar sangre según las leyes de kashrut antes de cocinar.";
                    $tips[] = "Usar utensilios exclusivos para carne según las reglas de separación.";
                    break;
            }
        }

        return $tips;
    }

    /**
     * Generar recomendaciones de almacenamiento
     */
    private function generateStorageRecommendations(Product $product)
    {
        $recommendations = [];
        $category = $product->category;
        
        if ($category) {
            switch ($category->name) {
                case 'Vinos':
                    $recommendations[] = "Almacenar horizontalmente en lugar fresco y oscuro.";
                    $recommendations[] = "Mantener alejado de productos no kosher para evitar contaminación.";
                    break;
                    
                case 'Pan':
                    $recommendations[] = "Almacenar en envoltorio kosher para mantener frescura.";
                    $recommendations[] = "Consumir dentro de 3-4 días para mejor calidad y sabor.";
                    break;
                    
                case 'Quesos':
                    $recommendations[] = "Refrigerar en contenedor kosher separado de productos cárnicos.";
                    $recommendations[] = "Usar papel kosher para envolver y mantener frescura.";
                    break;
                    
                case 'Carnes':
                    $recommendations[] = "Congelar rápidamente si no se consumirá en 2 días.";
                    $recommendations[] = "Descongelar en refrigerador kosher para mantener seguridad alimentaria.";
                    break;
            }
        }

        return $recommendations;
    }

    /**
     * Guardar contenido humano en la base de datos
     */
    public function saveHumanContent(Product $product)
    {
        $content = $this->generateHumanContent($product);
        
        // Aquí podrías guardar en una tabla específica de human_content
        // Por ahora, lo guardamos en el campo description del producto
        $description = "=== CONTENIDO EXCLUSIVO KOSHER ===\n\n";
        
        if (!empty($content['rabbinical_notes'])) {
            $description .= "NOTAS RABÍNICAS:\n" . implode("\n", $content['rabbinical_notes']) . "\n\n";
        }
        
        if (!empty($content['usage_recommendations'])) {
            $description .= "RECOMENDACIONES DE USO:\n" . implode("\n", $content['usage_recommendations']) . "\n\n";
        }
        
        if (!empty($content['recipes'])) {
            $description .= "RECETAS KOSHER:\n";
            foreach ($content['recipes'] as $recipe) {
                $description .= "- {$recipe['name']}: {$recipe['description']}\n";
            }
            $description .= "\n";
        }
        
        if (!empty($content['cultural_context'])) {
            $description .= "CONTEXTO CULTURAL:\n" . implode("\n", $content['cultural_context']) . "\n\n";
        }
        
        if (!empty($content['expert_review'])) {
            $description .= "RESEÑA DE EXPERTO:\n" . implode("\n", $content['expert_review']) . "\n\n";
        }
        
        if (!empty($content['community_feedback'])) {
            $description .= "RETROALIMENTACIÓN COMUNITARIA:\n" . implode("\n", $content['community_feedback']) . "\n\n";
        }
        
        if (!empty($content['preparation_tips'])) {
            $description .= "TIPS DE PREPARACIÓN:\n" . implode("\n", $content['preparation_tips']) . "\n\n";
        }
        
        if (!empty($content['storage_recommendations'])) {
            $description .= "ALMACENAMIENTO:\n" . implode("\n", $content['storage_recommendations']);
        }
        
        $product->description = $description;
        $product->save();
        
        Log::info("Human content generated for product: {$product->name}");
        
        return $content;
    }

    /**
     * Generar contenido para múltiples productos
     */
    public function generateBatchContent($limit = 50)
    {
        $products = Product::whereNull('description')
            ->orWhere('description', 'LIKE', '%Producto importado%')
            ->limit($limit)
            ->get();

        $generated = 0;
        
        foreach ($products as $product) {
            $this->saveHumanContent($product);
            $generated++;
            
            if ($generated % 10 === 0) {
                echo "Generados {$generated} productos con contenido humano...\n";
            }
        }
        
        return $generated;
    }
}
