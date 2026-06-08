<?php

namespace App\Services;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Support\Facades\DB;

class CategoryMigrationService
{
    /**
     * Mapeo de categorías antiguas a nuevas categorías del árbol
     */
    private $categoryMapping = [
        // Bebidas
        'gaseosas' => 'gaseosas',
        'bebidas' => 'bebidas',
        'jugos' => 'jugos-y-zumos',
        'agua' => 'agua',
        'cerveza' => 'cerveza',
        'vinos' => 'vinos',
        'whisky' => 'whisky',
        'tequila' => 'tequila',
        'sidras' => 'sidras',
        
        // Lácteos
        'leche' => 'leche',
        'quesos' => 'quesos',
        'yogurt' => 'yogur-lacteo',
        'crema láctea' => 'crema-lactea',
        'manteca' => 'manteca-y-margarina',
        'margarina' => 'manteca-y-margarina',
        'quesos no lácteos' => 'quesos-no-lacteos',
        'leches vegetales' => 'leches-vegetales',
        'crema no lactea' => 'crema-no-lactea',
        
        // Panadería y Cereales
        'pan' => 'pan',
        'cereales' => 'cereales-panaderia',
        'fideos' => 'fideos-y-pastas',
        'avena' => 'avena',
        'arroz' => 'arroz',
        'galletas' => 'galletas-y-crackers',
        'pan rallado' => 'pan-rallado-y-rebozadores',
        'harinas' => 'harinas-y-premezclas',
        
        // Carnes y Proteínas
        'carnes' => 'carnes-rojas',
        'pescados' => 'pescados-y-mariscos',
        'hamburguesas' => 'hamburguesas',
        'milanesas' => 'milanesas',
        'huevos' => 'huevos',
        'embutidos' => 'embutidos',
        
        // Frutas y Verduras
        'frutas' => 'frutas-frescas',
        'verduras' => 'verduras-frescas',
        'legumbres' => 'legumbres',
        'frutos secos' => 'frutos-secos-y-semillas',
        'conservas' => 'conservas',
        'ensaladas' => 'ensaladas-preparadas',
        
        // Dulces y Postres
        'chocolates' => 'chocolates',
        'caramelos' => 'caramelos-y-chicles',
        'chicles' => 'caramelos-y-chicles',
        'mermeladas' => 'mermeladas-y-dulces',
        'helados' => 'helados-y-sorbetes',
        'postres' => 'postres-en-polvo',
        'alfajores' => 'alfajores',
        'tortas' => 'tortas-y-budines',
        
        // Snacks y Copetín
        'papas' => 'papas-fritas',
        'snacks' => 'snacks-de-maiz',
        'aceitunas' => 'aceitunas',
        'picadas' => 'picadas',
        'barritas' => 'barritas-energeticas',
        
        // Condimentos y Aderezos
        'especias' => 'especias-y-condimentos',
        'condimentos' => 'especias-y-condimentos',
        'salsas' => 'salsas',
        'aderezos' => 'aderezos',
        'aceites' => 'aceites-y-vinagres',
        'vinagre' => 'aceites-y-vinagres',
        'sal' => 'sal',
        'mostaza' => 'mostaza-y-ketchup',
        'ketchup' => 'mostaza-y-ketchup',
        'salsa de soja' => 'salsa-de-soja',
        
        // Productos Naturistas
        'naturistas' => 'productos-naturistas',
        'naturales' => 'productos-naturistas',
        'organicos' => 'productos-organicos',
        'suplementos' => 'suplementos',
        
        // Congelados
        'congelados' => 'congelados',
        
        // Desayuno y Merienda
        'desayuno' => 'cereales-desayuno',
        'merienda' => 'barritas-cereal',
        
        // Cocina y Hogar
        'cocina' => 'articulos-cocina',
        'limpieza' => 'limpieza-general',
        'articulos' => 'articulos-cocina',
        
        // Salud y Bienestar
        'salud' => 'vitaminas-suplementos',
        'medicamentos' => 'medicamentos',
        'higiene' => 'productos-higiene',
    ];

    /**
     * Palabras clave para categorización automática
     */
    private $keywordMapping = [
        // Bebidas alcohólicas
        'whisky' => 'whisky',
        'vodka' => 'vodka',
        'ron' => 'ron',
        'licor' => 'licores',
        'tequila' => 'tequila',
        'vino' => 'vinos',
        'cerveza' => 'cerveza',
        'sidra' => 'sidras',
        
        // Bebidas no alcohólicas
        'gaseosa' => 'gaseosas',
        'soda' => 'gaseosas',
        'jugo' => 'jugos-y-zumos',
        'zumo' => 'jugos-y-zumos',
        'agua' => 'agua',
        'energética' => 'bebidas-energeticas',
        'té' => 'te-e-infusiones',
        'infusión' => 'te-e-infusiones',
        'café' => 'cafe',
        'soja' => 'bebidas-de-soja',
        
        // Lácteos
        'leche' => 'leche',
        'queso' => 'quesos',
        'yogur' => 'yogur-lacteo',
        'crema' => 'crema-lactea',
        'manteca' => 'manteca-y-margarina',
        'margarina' => 'manteca-y-margarina',
        
        // Panadería
        'pan' => 'pan',
        'fideo' => 'fideos-y-pastas',
        'pasta' => 'fideos-y-pastas',
        'avena' => 'avena',
        'arroz' => 'arroz',
        'galleta' => 'galletas-y-crackers',
        'cracker' => 'galletas-y-crackers',
        
        // Carnes
        'carne' => 'carnes-rojas',
        'pollo' => 'carnes-blancas',
        'pescado' => 'pescados-y-mariscos',
        'marisco' => 'pescados-y-mariscos',
        'hamburguesa' => 'hamburguesas',
        'milanesa' => 'milanesas',
        'huevo' => 'huevos',
        
        // Frutas y Verduras
        'fruta' => 'frutas-frescas',
        'verdura' => 'verduras-frescas',
        'legumbre' => 'legumbres',
        'ensalada' => 'ensaladas-preparadas',
        
        // Dulces
        'chocolate' => 'chocolates',
        'caramelo' => 'caramelos-y-chicles',
        'chicle' => 'caramelos-y-chicles',
        'mermelada' => 'mermeladas-y-dulces',
        'dulce' => 'mermeladas-y-dulces',
        'helado' => 'helados-y-sorbetes',
        'postre' => 'postres-en-polvo',
        'alfajor' => 'alfajores',
        'torta' => 'tortas-y-budines',
        'budín' => 'tortas-y-budines',
        
        // Snacks
        'papa' => 'papas-fritas',
        'papa frita' => 'papas-fritas',
        'snack' => 'snacks-de-maiz',
        'aceituna' => 'aceitunas',
        
        // Condimentos
        'especia' => 'especias-y-condimentos',
        'condimento' => 'especias-y-condimentos',
        'salsa' => 'salsas',
        'aderezo' => 'aderezos',
        'aceite' => 'aceites-y-vinagres',
        'vinagre' => 'aceites-y-vinagres',
        'mostaza' => 'mostaza-y-ketchup',
        'ketchup' => 'mostaza-y-ketchup',
        'soja' => 'salsa-de-soja',
        
        // Naturistas
        'naturista' => 'productos-naturistas',
        'natural' => 'productos-naturistas',
        'orgánico' => 'productos-organicos',
        'suplemento' => 'suplementos',
        
        // Cocina
        'limpieza' => 'limpieza-general',
        'cocina' => 'articulos-cocina',
        
        // Salud
        'medicamento' => 'medicamentos',
        'higiene' => 'productos-higiene',
        'vitamina' => 'vitaminas-suplementos',
    ];

    /**
     * Migrar todos los productos a la nueva estructura de categorías
     */
    public function migrateAllProducts($dryRun = false)
    {
        $products = Product::whereNotNull('category_id')->get();
        $migrated = 0;
        $notFound = 0;
        $byKeyword = 0;
        
        echo "Iniciando migración de {$products->count()} productos...\n";
        
        foreach ($products as $product) {
            $oldCategory = $product->category;
            if (!$oldCategory) {
                $notFound++;
                continue;
            }
            
            $newCategorySlug = $this->findNewCategory($oldCategory->name, $product->name);
            
            if ($newCategorySlug) {
                $newCategory = Category::where('slug', $newCategorySlug)->first();
                if ($newCategory) {
                    if (!$dryRun) {
                        $product->category_id = $newCategory->id;
                        $product->save();
                    }
                    
                    $migrated++;
                    echo "Migrado: '{$oldCategory->name}' -> '{$newCategory->name}' ({$product->name})\n";
                } else {
                    $notFound++;
                    echo "ERROR: Nueva categoría no encontrada: {$newCategorySlug}\n";
                }
            } else {
                $notFound++;
                echo "No se encontró mapeo para: '{$oldCategory->name}' ({$product->name})\n";
            }
        }
        
        echo "\n=== RESUMEN DE MIGRACIÓN ===\n";
        echo "Productos migrados: {$migrated}\n";
        echo "Por palabras clave: {$byKeyword}\n";
        echo "No encontrados: {$notFound}\n";
        echo "Total procesados: " . ($migrated + $notFound) . "\n";
        
        return [
            'migrated' => $migrated,
            'not_found' => $notFound,
            'by_keyword' => $byKeyword,
        ];
    }

    /**
     * Encontrar la nueva categoría basada en el nombre de la categoría antigua y el producto
     */
    private function findNewCategory($oldCategoryName, $productName)
    {
        // Primero intentar mapeo directo
        $oldSlug = strtolower(str_replace(' ', '', $oldCategoryName));
        if (isset($this->categoryMapping[$oldSlug])) {
            return $this->categoryMapping[$oldSlug];
        }
        
        // Buscar coincidencias parciales
        foreach ($this->categoryMapping as $key => $value) {
            if (str_contains($oldSlug, $key) || str_contains($key, $oldSlug)) {
                return $value;
            }
        }
        
        // Si no encuentra, analizar por palabras clave del producto
        return $this->categorizeByKeywords($productName);
    }

    /**
     * Categorizar producto basado en palabras clave del nombre
     */
    public function categorizeByKeywords($productName)
    {
        $productName = strtolower($productName);
        
        foreach ($this->keywordMapping as $keyword => $categorySlug) {
            if (str_contains($productName, $keyword)) {
                echo "Categorizado por palabra clave '{$keyword}': {$categorySlug}\n";
                return $categorySlug;
            }
        }
        
        return null;
    }

    /**
     * Generar reporte de categorías que necesitan mapeo manual
     */
    public function generateMigrationReport()
    {
        $products = Product::whereNotNull('category_id')->get();
        $categories = [];
        
        foreach ($products as $product) {
            $oldCategory = $product->category;
            if ($oldCategory) {
                $categories[$oldCategory->name][] = $product->name;
            }
        }
        
        echo "=== REPORTE DE CATEGORÍAS PARA MIGRAR ===\n";
        
        foreach ($categories as $categoryName => $products) {
            $count = count($products);
            echo "\n{$categoryName} ({$count} productos):\n";
            
            // Mostrar primeros 3 productos como ejemplo
            foreach (array_slice($products, 0, 3) as $productName) {
                echo "  - {$productName}\n";
            }
            
            if ($count > 3) {
                echo "  ... y " . ($count - 3) . " más\n";
            }
        }
        
        return $categories;
    }

    /**
     * Crear mapeo personalizado para categorías específicas
     */
    public function addCustomMapping($oldCategorySlug, $newCategorySlug)
    {
        $this->categoryMapping[$oldCategorySlug] = $newCategorySlug;
    }

    /**
     * Agregar palabra clave para categorización
     */
    public function addKeywordMapping($keyword, $categorySlug)
    {
        $this->keywordMapping[$keyword] = $categorySlug;
    }
}
