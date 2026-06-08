<?php

namespace App\Services;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Support\Facades\Log;

class AutoCategorizationService
{
    private $categoryMigrationService;

    public function __construct(CategoryMigrationService $categoryMigrationService)
    {
        $this->categoryMigrationService = $categoryMigrationService;
    }

    /**
     * Categorizar automáticamente un producto basado en su nombre y descripción
     */
    public function categorizeProduct(Product $product)
    {
        // Si ya tiene categoría, devolverla
        if ($product->category_id) {
            return $product->category;
        }

        $categorySlug = $this->determineCategory($product->name, $product->description ?? '');
        
        if ($categorySlug) {
            $category = Category::where('slug', $categorySlug)->first();
            if ($category) {
                // Solo guardar si el producto ya existe en la base de datos
                if ($product->id) {
                    $product->category_id = $category->id;
                    $product->save();
                    
                    Log::info("Producto categorizado automáticamente: {$product->name} -> {$category->name}");
                }
                return $category;
            }
        }

        Log::warning("No se pudo categorizar producto: {$product->name}");
        return null;
    }

    /**
     * Determinar la categoría más apropiada para un producto
     */
    private function determineCategory($productName, $description = '')
    {
        $text = strtolower($productName . ' ' . $description);
        
        // Mapeo avanzado de categorías con mayor precisión
        $categoryRules = [
            // Bebidas Alcohólicas (prioridad alta)
            'whisky' => ['whisky', 'whiskey', 'scotch', 'bourbon'],
            'vodka' => ['vodka'],
            'ron' => ['ron', 'rum'],
            'tequila' => ['tequila', 'mezcal'],
            'licores' => ['licor', 'liqueur', 'crema de licor'],
            'vinos' => ['vino', 'wine'],
            'cerveza' => ['cerveza', 'beer'],
            'sidras' => ['sidra', 'cider'],
            
            // Bebidas (prioridad alta)
            'gaseosas' => ['gaseosa', 'soda', 'refresco', 'cola', 'pepsi', 'coca'],
            'jugos-y-zumos' => ['jugo', 'zumo', 'juice', 'nectar'],
            'agua' => ['agua', 'water'],
            'bebidas-energeticas' => ['energética', 'energy', 'red bull'],
            'te-e-infusiones' => ['té', 'te', 'infusión', 'tea', 'herbal'],
            'cafe' => ['café', 'coffee'],
            'bebidas-de-soja' => ['soja', 'soy'],
            'leche-chocolatada' => ['chocolate milk', 'leche chocolatada'],
            
            // Lácteos
            'leche' => ['leche', 'milk'],
            'quesos' => ['queso', 'cheese'],
            'yogur-lacteo' => ['yogur', 'yogurt', 'yoghurt'],
            'crema-lactea' => ['crema', 'cream'],
            'manteca-y-margarina' => ['manteca', 'margarina', 'butter'],
            'quesos-no-lacteos' => ['vegano', 'plant based', 'no lácteo'],
            'leches-vegetales' => ['almendra', 'avena', 'soja', 'coco'],
            
            // Panadería y Cereales
            'pan' => ['pan', 'bread', 'bagel', 'croissant'],
            'cereales-panaderia' => ['cereal', 'granola', 'muesli'],
            'fideos-y-pastas' => ['fideo', 'pasta', 'spaghetti', 'macarrones'],
            'avena' => ['avena', 'oat', 'oatmeal'],
            'arroz' => ['arroz', 'rice'],
            'galletas-y-crackers' => ['galleta', 'cracker', 'cookie', 'biscuit'],
            'pan-rallado-y-rebozadores' => ['pan rallado', 'rebozador', 'breadcrumb'],
            'harinas-y-premezclas' => ['harina', 'flour', 'premezcla'],
            
            // Carnes y Proteínas
            'carnes-rojas' => ['carne', 'beef', 'res', 'vacuno'],
            'carnes-blancas' => ['pollo', 'chicken', 'pavo', 'turkey'],
            'pescados-y-mariscos' => ['pescado', 'marisco', 'fish', 'seafood'],
            'hamburguesas' => ['hamburguesa', 'burger'],
            'milanesas' => ['milanesa', 'breaded'],
            'proteinas-vegetales' => ['vegetal', 'plant based', 'veggie'],
            'huevos' => ['huevo', 'egg'],
            'embutidos' => ['embutido', 'salchicha', 'chorizo'],
            
            // Frutas y Verduras
            'frutas-frescas' => ['fruta', 'fruit'],
            'verduras-frescas' => ['verdura', 'vegetal', 'vegetable'],
            'frutas-enlatadas' => ['fruta enlatada', 'canned fruit'],
            'verduras-enlatadas' => ['verdura enlatada', 'canned vegetable'],
            'ensaladas-preparadas' => ['ensalada', 'salad'],
            'legumbres' => ['legumbre', 'lenteja', 'garbanzo', 'frijol', 'bean'],
            'frutos-secos-y-semillas' => ['fruto seco', 'nuez', 'almendra', 'avellana', 'semilla'],
            'conservas' => ['conserva', 'preserved'],
            
            // Dulces y Postres
            'chocolates' => ['chocolate', 'cacao'],
            'caramelos-y-chicles' => ['caramelo', 'chicle', 'candy', 'gum'],
            'mermeladas-y-dulces' => ['mermelada', 'dulce', 'jam', 'jelly'],
            'helados-y-sorbetes' => ['helado', 'sorbete', 'ice cream', 'sorbet'],
            'postres-en-polvo' => ['flan', 'postre en polvo', 'gelatina'],
            'galletas-dulces' => ['galleta dulce', 'alfajor', 'cookie'],
            'alfajores' => ['alfajor'],
            'tortas-y-budines' => ['torta', 'budín', 'cake', 'muffin'],
            
            // Snacks y Copetín
            'papas-fritas' => ['papa frita', 'papas fritas', 'french fries', 'chip'],
            'snacks-de-maiz' => ['snack', 'palomita', 'popcorn'],
            'mix-frutos-secos' => ['mix', 'trail mix'],
            'aceitunas' => ['aceituna', 'olive'],
            'picadas' => ['picada', 'fiambre'],
            'barritas-energeticas' => ['barrita', 'energy bar'],
            'snacks-saludables' => ['snack saludable', 'healthy snack'],
            'copetin-variado' => ['copetín', 'appetizer'],
            
            // Condimentos y Aderezos
            'especias-y-condimentos' => ['especia', 'condimento', 'spice', 'seasoning'],
            'salsas' => ['salsa', 'sauce'],
            'aderezos' => ['aderezo', 'dressing'],
            'aceites-y-vinagres' => ['aceite', 'vinagre', 'oil', 'vinegar'],
            'sal' => ['sal', 'salt'],
            'mostaza-y-ketchup' => ['mostaza', 'ketchup', 'mustard'],
            'salsa-de-soja' => ['soja', 'soy sauce'],
            'hierbas-aromaticas' => ['hierba', 'aromática', 'herb'],
            
            // Productos Naturistas
            'cereales-integrales' => ['integral', 'whole grain'],
            'semillas-y-granos' => ['semilla', 'grano', 'seed', 'grain'],
            'suplementos' => ['suplemento', 'vitamina', 'supplement'],
            'productos-organicos' => ['orgánico', 'organic'],
            'aceites-esenciales' => ['aceite esencial', 'essential oil'],
            'tes-herbales' => ['té herbal', 'herbal tea'],
            'endulzantes-naturales' => ['endulzante', 'stevia', 'natural sweetener'],
            'harinas-alternativas' => ['harina alternativa', 'alternative flour'],
            
            // Congelados
            'verduras-congeladas' => ['verdura congelada', 'frozen vegetable'],
            'frutas-congeladas' => ['fruta congelada', 'frozen fruit'],
            'carnes-congeladas' => ['carne congelada', 'frozen meat'],
            'comidas-preparadas' => ['comida preparada', 'prepared meal'],
            'postres-congelados' => ['postre congelado', 'frozen dessert'],
            'papas-congeladas' => ['papa congelada', 'frozen potato'],
            'mariscos-congelados' => ['marisco congelado', 'frozen seafood'],
            
            // Desayuno y Merienda
            'cereales-desayuno' => ['cereal desayuno', 'breakfast cereal'],
            'barritas-cereal' => ['barrita cereal', 'cereal bar'],
            'tostadas-y-pan' => ['tostada', 'toast'],
            'mermeladas-desayuno' => ['mermelada', 'jam'],
            'miel' => ['miel', 'honey'],
            'yogur-desayuno' => ['yogur desayuno', 'breakfast yogurt'],
            'frutas-desayuno' => ['fruta desayuno', 'breakfast fruit'],
            'jugos-desayuno' => ['jugo desayuno', 'breakfast juice'],
            
            // Cocina y Hogar
            'limpieza-cocina' => ['limpieza cocina', 'kitchen cleaner'],
            'articulos-cocina' => ['artículo cocina', 'kitchen utensil'],
            'aluminio-envolturas' => ['aluminio', 'envoltura', 'aluminum foil'],
            'servilletas-papel' => ['servilleta', 'papel', 'napkin', 'paper'],
            'bolsas-contenedores' => ['bolsa', 'contenedor', 'bag', 'container'],
            'limpieza-general' => ['limpieza', 'detergente', 'cleaner'],
            'organizacion' => ['organización', 'storage'],
            'electrodomesticos' => ['electrodoméstico', 'appliance'],
            
            // Salud y Bienestar
            'vitaminas-suplementos' => ['vitamina', 'suplemento', 'vitamin'],
            'medicamentos' => ['medicamento', 'medicine', 'pharmaceutical'],
            'productos-higiene' => ['higiene', 'hygiene'],
            'cuidado-personal' => ['cuidado personal', 'personal care'],
            'productos-naturales-salud' => ['natural salud', 'natural health'],
            'te-salud' => ['té salud', 'health tea'],
            'alimentos-funcionales' => ['alimento funcional', 'functional food'],
            'bebidas-saludables' => ['bebida saludable', 'healthy drink'],
        ];

        // Buscar coincidencias con prioridad
        foreach ($categoryRules as $categorySlug => $keywords) {
            foreach ($keywords as $keyword) {
                if (str_contains($text, $keyword)) {
                    Log::info("Categoría detectada por palabra clave '{$keyword}': {$categorySlug}");
                    return $categorySlug;
                }
            }
        }

        // Si no encuentra coincidencias exactas, usar el servicio de migración
        return $this->categoryMigrationService->categorizeByKeywords($productName);
    }

    /**
     * Categorizar múltiples productos en lote
     */
    public function categorizeBatch($limit = 100)
    {
        $products = Product::whereNull('category_id')->limit($limit)->get();
        $categorized = 0;

        foreach ($products as $product) {
            if ($this->categorizeProduct($product)) {
                $categorized++;
            }
        }

        return $categorized;
    }

    /**
     * Obtener estadísticas de categorización
     */
    public function getCategorizationStats()
    {
        $total = Product::count();
        $categorized = Product::whereNotNull('category_id')->count();
        $uncategorized = Product::whereNull('category_id')->count();

        return [
            'total' => $total,
            'categorized' => $categorized,
            'uncategorized' => $uncategorized,
            'percentage' => $total > 0 ? round(($categorized / $total) * 100, 2) : 0,
        ];
    }

    /**
     * Mejorar categorización existente basada en feedback
     */
    public function improveCategorization($productSlug, $correctCategorySlug)
    {
        $product = Product::where('slug', $productSlug)->first();
        if ($product) {
            $category = Category::where('slug', $correctCategorySlug)->first();
            if ($category) {
                $product->category_id = $category->id;
                $product->save();
                
                Log::info("Categorización corregida: {$product->name} -> {$category->name}");
                return true;
            }
        }
        return false;
    }
}
