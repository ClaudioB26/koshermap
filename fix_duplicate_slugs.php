<?php

require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "=== IDENTIFICANDO SLUGS DUPLICADOS ===\n";

// Buscar slugs duplicados en la estructura
$duplicates = [
    'frutos-secos' => ['Snacks y Copetín', 'Frutas y Verduras'],
    'yogurt' => ['Lácteos y Derivados', 'Desayuno y Merienda'],
    'jugo' => ['Bebidas', 'Desayuno y Merienda'],
    'helados' => ['Dulces y Postres', 'Congelados'],
    'cereales' => ['Panadería y Cereales', 'Desayuno y Merienda'],
    'te-infusiones' => ['Bebidas', 'Salud y Bienestar'],
    'productos-naturales' => ['Productos Naturistas', 'Salud y Bienestar'],
];

echo "Slugs duplicados encontrados:\n";
foreach ($duplicates as $slug => $categories) {
    echo "  - {$slug}: {$categories[0]} vs {$categories[1]}\n";
}

echo "\n=== CORRIGIENDO ESTRUCTURA ===\n";

// Nueva estructura corregida sin duplicados
$correctedTree = [
    [
        'name' => 'Alimentos y Bebidas',
        'slug' => 'alimentos-y-bebidas',
        'children' => [
            [
                'name' => 'Lácteos y Derivados',
                'slug' => 'lacteos-y-derivados',
                'children' => [
                    ['name' => 'Leche', 'slug' => 'leche'],
                    ['name' => 'Quesos', 'slug' => 'quesos'],
                    ['name' => 'Yogur Lácteo', 'slug' => 'yogur-lacteo'],
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
                'children' => [
                    ['name' => 'Gaseosas', 'slug' => 'gaseosas'],
                    ['name' => 'Jugos y Zumos', 'slug' => 'jugos-y-zumos'],
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
                'children' => [
                    ['name' => 'Pan', 'slug' => 'pan'],
                    ['name' => 'Cereales', 'slug' => 'cereales-panaderia'],
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
                'children' => [
                    ['name' => 'Frutas Frescas', 'slug' => 'frutas-frescas'],
                    ['name' => 'Verduras Frescas', 'slug' => 'verduras-frescas'],
                    ['name' => 'Frutas Enlatadas', 'slug' => 'frutas-enlatadas'],
                    ['name' => 'Verduras Enlatadas', 'slug' => 'verduras-enlatadas'],
                    ['name' => 'Ensaladas Preparadas', 'slug' => 'ensaladas-preparadas'],
                    ['name' => 'Legumbres', 'slug' => 'legumbres'],
                    ['name' => 'Frutos Secos y Semillas', 'slug' => 'frutos-secos-y-semillas'],
                    ['name' => 'Conservas', 'slug' => 'conservas'],
                ]
            ],
            [
                'name' => 'Dulces y Postres',
                'slug' => 'dulces-y-postres',
                'children' => [
                    ['name' => 'Chocolates', 'slug' => 'chocolates'],
                    ['name' => 'Caramelos y Chicles', 'slug' => 'caramelos-y-chicles'],
                    ['name' => 'Mermeladas y Dulces', 'slug' => 'mermeladas-y-dulces'],
                    ['name' => 'Helados y Sorbetes', 'slug' => 'helados-y-sorbetes'],
                    ['name' => 'Postres en Polvo', 'slug' => 'postres-en-polvo'],
                    ['name' => 'Galletas Dulces', 'slug' => 'galletas-dulces'],
                    ['name' => 'Alfajores', 'slug' => 'alfajores'],
                    ['name' => 'Tortas y Budines', 'slug' => 'tortas-y-budines'],
                ]
            ],
            [
                'name' => 'Snacks y Copetín',
                'slug' => 'snacks-y-copetin',
                'children' => [
                    ['name' => 'Papas Fritas', 'slug' => 'papas-fritas'],
                    ['name' => 'Snacks de Maíz', 'slug' => 'snacks-de-maiz'],
                    ['name' => 'Mix de Frutos Secos', 'slug' => 'mix-frutos-secos'],
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
                'children' => [
                    ['name' => 'Verduras Congeladas', 'slug' => 'verduras-congeladas'],
                    ['name' => 'Frutas Congeladas', 'slug' => 'frutas-congeladas'],
                    ['name' => 'Carnes Congeladas', 'slug' => 'carnes-congeladas'],
                    ['name' => 'Comidas Preparadas', 'slug' => 'comidas-preparadas'],
                    ['name' => 'Postres Congelados', 'slug' => 'postres-congelados'],
                    ['name' => 'Papas Congeladas', 'slug' => 'papas-congeladas'],
                    ['name' => 'Mariscos Congelados', 'slug' => 'mariscos-congelados'],
                ]
            ],
            [
                'name' => 'Desayuno y Merienda',
                'slug' => 'desayuno-y-merienda',
                'children' => [
                    ['name' => 'Cereales de Desayuno', 'slug' => 'cereales-desayuno'],
                    ['name' => 'Barritas de Cereal', 'slug' => 'barritas-cereal'],
                    ['name' => 'Tostadas y Pan', 'slug' => 'tostadas-y-pan'],
                    ['name' => 'Mermeladas', 'slug' => 'mermeladas-desayuno'],
                    ['name' => 'Miel', 'slug' => 'miel'],
                    ['name' => 'Yogur para Desayuno', 'slug' => 'yogur-desayuno'],
                    ['name' => 'Frutas', 'slug' => 'frutas-desayuno'],
                    ['name' => 'Jugos', 'slug' => 'jugos-desayuno'],
                ]
            ],
            [
                'name' => 'Cocina y Hogar',
                'slug' => 'cocina-y-hogar',
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
                'children' => [
                    ['name' => 'Vitaminas y Suplementos', 'slug' => 'vitaminas-suplementos'],
                    ['name' => 'Medicamentos', 'slug' => 'medicamentos'],
                    ['name' => 'Productos de Higiene', 'slug' => 'productos-higiene'],
                    ['name' => 'Cuidado Personal', 'slug' => 'cuidado-personal'],
                    ['name' => 'Productos Naturales Salud', 'slug' => 'productos-naturales-salud'],
                    ['name' => 'Té para Salud', 'slug' => 'te-salud'],
                    ['name' => 'Alimentos Funcionales', 'slug' => 'alimentos-funcionales'],
                    ['name' => 'Bebidas Saludables', 'slug' => 'bebidas-saludables'],
                ]
            ],
        ]
    ]
];

// Limpiar y crear nueva estructura
DB::table('categories')->delete();
echo "Categorías eliminadas\n";

// Función para crear árbol
function createCategoryTree($categories, $parentId = null) {
    foreach ($categories as $categoryData) {
        $category = \App\Models\Category::create([
            'name' => $categoryData['name'],
            'slug' => $categoryData['slug'],
            'parent_id' => $parentId,
        ]);

        if (isset($categoryData['children'])) {
            createCategoryTree($categoryData['children'], $category->id);
        }
    }
}

createCategoryTree($correctedTree);
echo "Nueva estructura creada exitosamente\n";

echo "\n=== VERIFICANDO ESTRUCTURA ===\n";
$allCategories = \App\Models\Category::orderBy('parent_id')->get();
foreach ($allCategories as $cat) {
    $parent = $cat->parent_id ? " (hijo de {$cat->parent_id})" : " (raíz)";
    echo "ID: {$cat->id} - {$cat->name} [{$cat->slug}]{$parent}\n";
}

echo "\n=== ESTRUCTURA COMPLETADA ===\n";
