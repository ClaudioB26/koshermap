<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;
use Illuminate\Support\Facades\DB;

class CategoryTreeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        // Limpiar categorías existentes
        DB::table('categories')->delete();
        
        // Estructura de árbol unificada para categorías kosher
        $categoryTree = [
            [
                'name' => 'Alimentos y Bebidas',
                'slug' => 'alimentos-y-bebidas',
                'description' => 'Productos alimenticios y bebidas en general',
                'children' => [
                    [
                        'name' => 'Lácteos y Derivados',
                        'slug' => 'lacteos-y-derivados',
                        'description' => 'Productos lácteos y sus alternativas',
                        'children' => [
                            ['name' => 'Leche', 'slug' => 'leche'],
                            ['name' => 'Quesos', 'slug' => 'quesos'],
                            ['name' => 'Yogurt', 'slug' => 'yogurt'],
                            ['name' => 'Crema Láctea', 'slug' => 'crema-lactea'],
                            ['name' => 'Manteca y Margarina', 'slug' => 'manteca-y-margarina'],
                            ['name' => 'Quesos No Lácteos', 'slug' => 'quesos-no-lacteos'],
                            ['name' => 'Leches Vegetales', 'slug' => 'leches-vegetales'],
                            ['name' => 'Crema No Láctea', 'slug' => 'crema-no-lactea'],
                        ]
                    ],
                    [
                        'name' => 'Bebidas',
                        'slug' => 'bebidas',
                        'description' => 'Todas las bebidas no alcohólicas',
                        'children' => [
                            ['name' => 'Gaseosas', 'slug' => 'gaseosas'],
                            ['name' => 'Jugos', 'slug' => 'jugos'],
                            ['name' => 'Agua', 'slug' => 'agua'],
                            ['name' => 'Bebidas Energéticas', 'slug' => 'bebidas-energeticas'],
                            ['name' => 'Té e Infusiones', 'slug' => 'te-e-infusiones'],
                            ['name' => 'Café', 'slug' => 'cafe'],
                            ['name' => 'Bebidas de Soja', 'slug' => 'bebidas-de-soja'],
                            ['name' => 'Leche Chocolatada', 'slug' => 'leche-chocolatada'],
                        ]
                    ],
                    [
                        'name' => 'Bebidas Alcohólicas',
                        'slug' => 'bebidas-alcoholicas',
                        'description' => 'Bebidas con contenido alcohólico',
                        'children' => [
                            ['name' => 'Vinos', 'slug' => 'vinos'],
                            ['name' => 'Cerveza', 'slug' => 'cerveza'],
                            ['name' => 'Whisky', 'slug' => 'whisky'],
                            ['name' => 'Vodka', 'slug' => 'vodka'],
                            ['name' => 'Sidras', 'slug' => 'sidras'],
                            ['name' => 'Licores', 'slug' => 'licores'],
                            ['name' => 'Tequila', 'slug' => 'tequila'],
                            ['name' => 'Ron', 'slug' => 'ron'],
                        ]
                    ],
                    [
                        'name' => 'Panadería y Cereales',
                        'slug' => 'panaderia-y-cereales',
                        'description' => 'Panes, cereales y productos de granos',
                        'children' => [
                            ['name' => 'Pan', 'slug' => 'pan'],
                            ['name' => 'Cereales', 'slug' => 'cereales'],
                            ['name' => 'Fideos y Pastas', 'slug' => 'fideos-y-pastas'],
                            ['name' => 'Avena', 'slug' => 'avena'],
                            ['name' => 'Arroz', 'slug' => 'arroz'],
                            ['name' => 'Galletas y Crackers', 'slug' => 'galletas-y-crackers'],
                            ['name' => 'Pan Rallado y Rebozadores', 'slug' => 'pan-rallado-y-rebozadores'],
                            ['name' => 'Harinas y Premezclas', 'slug' => 'harinas-y-premezclas'],
                        ]
                    ],
                    [
                        'name' => 'Carnes y Proteínas',
                        'slug' => 'carnes-y-proteinas',
                        'description' => 'Carnes, pescados y alternativas proteicas',
                        'children' => [
                            ['name' => 'Carnes Rojas', 'slug' => 'carnes-rojas'],
                            ['name' => 'Carnes Blancas', 'slug' => 'carnes-blancas'],
                            ['name' => 'Pescados y Mariscos', 'slug' => 'pescados-y-mariscos'],
                            ['name' => 'Hamburguesas', 'slug' => 'hamburguesas'],
                            ['name' => 'Milanesas', 'slug' => 'milanesas'],
                            ['name' => 'Proteínas Vegetales', 'slug' => 'proteinas-vegetales'],
                            ['name' => 'Huevos', 'slug' => 'huevos'],
                            ['name' => 'Embutidos', 'slug' => 'embutidos'],
                        ]
                    ],
                    [
                        'name' => 'Frutas y Verduras',
                        'slug' => 'frutas-y-verduras',
                        'description' => 'Productos frescos y procesados de origen vegetal',
                        'children' => [
                            ['name' => 'Frutas Frescas', 'slug' => 'frutas-frescas'],
                            ['name' => 'Verduras Frescas', 'slug' => 'verduras-frescas'],
                            ['name' => 'Frutas Enlatadas', 'slug' => 'frutas-enlatadas'],
                            ['name' => 'Verduras Enlatadas', 'slug' => 'verduras-enlatadas'],
                            ['name' => 'Ensaladas Preparadas', 'slug' => 'ensaladas-preparadas'],
                            ['name' => 'Legumbres', 'slug' => 'legumbres'],
                            ['name' => 'Frutos Secos', 'slug' => 'frutos-secos'],
                            ['name' => 'Conservas', 'slug' => 'conservas'],
                        ]
                    ],
                    [
                        'name' => 'Dulces y Postres',
                        'slug' => 'dulces-y-postres',
                        'description' => 'Productos dulces y postres',
                        'children' => [
                            ['name' => 'Chocolates', 'slug' => 'chocolates'],
                            ['name' => 'Caramelos y Chicles', 'slug' => 'caramelos-y-chicles'],
                            ['name' => 'Mermeladas y Dulces', 'slug' => 'mermeladas-y-dulces'],
                            ['name' => 'Helados', 'slug' => 'helados'],
                            ['name' => 'Postres en Polvo', 'slug' => 'postres-en-polvo'],
                            ['name' => 'Galletas Dulces', 'slug' => 'galletas-dulces'],
                            ['name' => 'Alfajores', 'slug' => 'alfajores'],
                            ['name' => 'Tortas y Budines', 'slug' => 'tortas-y-budines'],
                        ]
                    ],
                    [
                        'name' => 'Snacks y Copetín',
                        'slug' => 'snacks-y-copetin',
                        'description' => 'Productos para snacks y entretenimiento',
                        'children' => [
                            ['name' => 'Papas Fritas', 'slug' => 'papas-fritas'],
                            ['name' => 'Snacks de Maíz', 'slug' => 'snacks-de-maiz'],
                            ['name' => 'Frutos Secos', 'slug' => 'frutos-secos'],
                            ['name' => 'Aceitunas', 'slug' => 'aceitunas'],
                            ['name' => 'Picadas', 'slug' => 'picadas'],
                            ['name' => 'Barritas Energéticas', 'slug' => 'barritas-energeticas'],
                            ['name' => 'Snacks Saludables', 'slug' => 'snacks-saludables'],
                            ['name' => 'Copetín Variado', 'slug' => 'copetin-variado'],
                        ]
                    ],
                    [
                        'name' => 'Condimentos y Aderezos',
                        'slug' => 'condimentos-y-aderezos',
                        'description' => 'Especias, salsas y aderezos',
                        'children' => [
                            ['name' => 'Especias y Condimentos', 'slug' => 'especias-y-condimentos'],
                            ['name' => 'Salsas', 'slug' => 'salsas'],
                            ['name' => 'Aderezos', 'slug' => 'aderezos'],
                            ['name' => 'Aceites y Vinagres', 'slug' => 'aceites-y-vinagres'],
                            ['name' => 'Sal', 'slug' => 'sal'],
                            ['name' => 'Mostaza y Ketchup', 'slug' => 'mostaza-y-ketchup'],
                            ['name' => 'Salsa de Soja', 'slug' => 'salsa-de-soja'],
                            ['name' => 'Hierbas Aromáticas', 'slug' => 'hierbas-aromaticas'],
                        ]
                    ],
                    [
                        'name' => 'Productos Naturistas',
                        'slug' => 'productos-naturistas',
                        'description' => 'Productos naturales y orgánicos',
                        'children' => [
                            ['name' => 'Cereales Integrales', 'slug' => 'cereales-integrales'],
                            ['name' => 'Semillas y Granos', 'slug' => 'semillas-y-granos'],
                            ['name' => 'Suplementos', 'slug' => 'suplementos'],
                            ['name' => 'Productos Orgánicos', 'slug' => 'productos-organicos'],
                            ['name' => 'Aceites Esenciales', 'slug' => 'aceites-esenciales'],
                            ['name' => 'Tés Herbales', 'slug' => 'tes-herbales'],
                            ['name' => 'Endulzantes Naturales', 'slug' => 'endulzantes-naturales'],
                            ['name' => 'Harinas Alternativas', 'slug' => 'harinas-alternativas'],
                        ]
                    ],
                    [
                        'name' => 'Congelados',
                        'slug' => 'congelados',
                        'description' => 'Productos congelados',
                        'children' => [
                            ['name' => 'Verduras Congeladas', 'slug' => 'verduras-congeladas'],
                            ['name' => 'Frutas Congeladas', 'slug' => 'frutas-congeladas'],
                            ['name' => 'Carnes Congeladas', 'slug' => 'carnes-congeladas'],
                            ['name' => 'Comidas Preparadas', 'slug' => 'comidas-preparadas'],
                            ['name' => 'Helados', 'slug' => 'helados-congelados'],
                            ['name' => 'Postres Congelados', 'slug' => 'postres-congelados'],
                            ['name' => 'Papas Congeladas', 'slug' => 'papas-congeladas'],
                            ['name' => 'Mariscos Congelados', 'slug' => 'mariscos-congelados'],
                        ]
                    ],
                    [
                        'name' => 'Desayuno y Merienda',
                        'slug' => 'desayuno-y-merienda',
                        'description' => 'Productos para desayuno y merienda',
                        'children' => [
                            ['name' => 'Cereales de Desayuno', 'slug' => 'cereales-desayuno'],
                            ['name' => 'Barritas de Cereal', 'slug' => 'barritas-cereal'],
                            ['name' => 'Tostadas y Pan', 'slug' => 'tostadas-y-pan'],
                            ['name' => 'Mermeladas', 'slug' => 'mermeladas'],
                            ['name' => 'Miel', 'slug' => 'miel'],
                            ['name' => 'Yogurt', 'slug' => 'yogurt-desayuno'],
                            ['name' => 'Frutas', 'slug' => 'frutas-desayuno'],
                            ['name' => 'Jugos', 'slug' => 'jugos-desayuno'],
                        ]
                    ],
                    [
                        'name' => 'Cocina y Hogar',
                        'slug' => 'cocina-y-hogar',
                        'description' => 'Artículos para cocina y limpieza del hogar',
                        'children' => [
                            ['name' => 'Limpieza de Cocina', 'slug' => 'limpieza-cocina'],
                            ['name' => 'Artículos de Cocina', 'slug' => 'articulos-cocina'],
                            ['name' => 'Aluminio y Envolturas', 'slug' => 'aluminio-envolturas'],
                            ['name' => 'Servilletas y Papel', 'slug' => 'servilletas-papel'],
                            ['name' => 'Bolsas y Contenedores', 'slug' => 'bolsas-contenedores'],
                            ['name' => 'Limpieza General', 'slug' => 'limpieza-general'],
                            ['name' => 'Organización', 'slug' => 'organizacion'],
                            ['name' => 'Electrodomésticos', 'slug' => 'electrodomesticos'],
                        ]
                    ],
                    [
                        'name' => 'Salud y Bienestar',
                        'slug' => 'salud-y-bienestar',
                        'description' => 'Productos para la salud y bienestar',
                        'children' => [
                            ['name' => 'Vitaminas y Suplementos', 'slug' => 'vitaminas-suplementos'],
                            ['name' => 'Medicamentos', 'slug' => 'medicamentos'],
                            ['name' => 'Productos de Higiene', 'slug' => 'productos-higiene'],
                            ['name' => 'Cuidado Personal', 'slug' => 'cuidado-personal'],
                            ['name' => 'Productos Naturales', 'slug' => 'productos-naturales'],
                            ['name' => 'Té y Infusiones', 'slug' => 'te-infusiones'],
                            ['name' => 'Alimentos Funcionales', 'slug' => 'alimentos-funcionales'],
                            ['name' => 'Bebidas Saludables', 'slug' => 'bebidas-saludables'],
                        ]
                    ],
                ]
            ]
        ];

        // Crear la estructura de árbol
        $this->createCategoryTree($categoryTree);
        
        $this->command->info('Estructura de categorías creada exitosamente');
    }

    private function createCategoryTree($categories, $parentId = null)
    {
        foreach ($categories as $categoryData) {
            // Generar slug único usando el helper de Laravel
            $baseSlug = $categoryData['slug'];
            $slug = $baseSlug;
            $counter = 1;
            
            while (Category::where('slug', $slug)->exists()) {
                $slug = $baseSlug . '-' . $counter;
                $counter++;
            }

            $category = Category::create([
                'name' => $categoryData['name'],
                'slug' => $slug,
                'parent_id' => $parentId,
            ]);

            // Si tiene hijos, crearlos recursivamente
            if (isset($categoryData['children'])) {
                $this->createCategoryTree($categoryData['children'], $category->id);
            }
        }
    }
}
