<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    public function run()
    {
        // 1. Define Categories with Translations and Hierarchy
        $categories = [
            [
                'name' => [
                    'es' => 'Alimentos', 
                    'en' => 'Food', 
                    'pt' => 'Alimentos', 
                    'fr' => 'Aliments', 
                    'he' => 'מזון', 
                    'ru' => 'Еда'
                ],
                'slug' => 'alimentos',
                'children' => [
                    [
                        'name' => [
                            'es' => 'Lácteos', 
                            'en' => 'Dairy', 
                            'pt' => 'Laticínios', 
                            'fr' => 'Produits laitiers', 
                            'he' => 'מוצרי חלב', 
                            'ru' => 'Молочные продукты'
                        ],
                        'slug' => 'lacteos',
                        'children' => [
                            ['name' => ['es' => 'Quesos', 'en' => 'Cheese', 'pt' => 'Queijos', 'fr' => 'Fromages', 'he' => 'גבינות', 'ru' => 'Сыр'], 'slug' => 'quesos'],
                            ['name' => ['es' => 'Leche', 'en' => 'Milk', 'pt' => 'Leite', 'fr' => 'Lait', 'he' => 'חלב', 'ru' => 'Молоко'], 'slug' => 'leche'],
                            ['name' => ['es' => 'Yogurt', 'en' => 'Yogurt', 'pt' => 'Iogurte', 'fr' => 'Yaourt', 'he' => 'יוגורט', 'ru' => 'Йогурт'], 'slug' => 'yogurt'],
                            ['name' => ['es' => 'Mantequilla', 'en' => 'Butter', 'pt' => 'Manteiga', 'fr' => 'Beurre', 'he' => 'חמאה', 'ru' => 'Масло'], 'slug' => 'mantequilla'],
                            ['name' => ['es' => 'Helados', 'en' => 'Ice Cream', 'pt' => 'Sorvetes', 'fr' => 'Glaces', 'he' => 'גלידות', 'ru' => 'Мороженое'], 'slug' => 'helados'],
                        ]
                    ],
                    [
                        'name' => [
                            'es' => 'Carnes', 
                            'en' => 'Meat', 
                            'pt' => 'Carnes', 
                            'fr' => 'Viandes', 
                            'he' => 'בשר', 
                            'ru' => 'Мясо'
                        ],
                        'slug' => 'carnes',
                        'children' => [
                            ['name' => ['es' => 'Vacuno', 'en' => 'Beef', 'pt' => 'Carne Bovina', 'fr' => 'Bœuf', 'he' => 'בקר', 'ru' => 'Говядина'], 'slug' => 'vacuno'],
                            ['name' => ['es' => 'Pollo', 'en' => 'Chicken', 'pt' => 'Frango', 'fr' => 'Poulet', 'he' => 'עוף', 'ru' => 'Курица'], 'slug' => 'pollo'],
                            ['name' => ['es' => 'Embutidos', 'en' => 'Cold Cuts', 'pt' => 'Frios', 'fr' => 'Charcuterie', 'he' => 'נקניקים', 'ru' => 'Колбасные изделия'], 'slug' => 'embutidos'],
                        ]
                    ],
                    [
                        'name' => [
                            'es' => 'Pescados', 
                            'en' => 'Fish', 
                            'pt' => 'Peixes', 
                            'fr' => 'Poissons', 
                            'he' => 'דגים', 
                            'ru' => 'Рыба'
                        ],
                        'slug' => 'pescados',
                        'children' => [
                             ['name' => ['es' => 'Atún', 'en' => 'Tuna', 'pt' => 'Atum', 'fr' => 'Thon', 'he' => 'טונה', 'ru' => 'Тунец'], 'slug' => 'atun'],
                             ['name' => ['es' => 'Salmón', 'en' => 'Salmon', 'pt' => 'Salmão', 'fr' => 'Saumon', 'he' => 'סלמון', 'ru' => 'Лосось'], 'slug' => 'salmon'],
                             ['name' => ['es' => 'Conservas de Pescado', 'en' => 'Canned Fish', 'pt' => 'Conservas de Peixe', 'fr' => 'Conserves de poisson', 'he' => 'שימורי דגים', 'ru' => 'Рыбные консервы'], 'slug' => 'conservas-pescado'],
                        ]
                    ],
                    [
                        'name' => [
                            'es' => 'Panadería', 
                            'en' => 'Bakery', 
                            'pt' => 'Padaria', 
                            'fr' => 'Boulangerie', 
                            'he' => 'מאפים', 
                            'ru' => 'Выпечка'
                        ],
                        'slug' => 'panaderia',
                        'children' => [
                             ['name' => ['es' => 'Pan', 'en' => 'Bread', 'pt' => 'Pão', 'fr' => 'Pain', 'he' => 'לחם', 'ru' => 'Хлеб'], 'slug' => 'pan'],
                             ['name' => ['es' => 'Galletas', 'en' => 'Cookies', 'pt' => 'Biscoitos', 'fr' => 'Biscuits', 'he' => 'עוגיות', 'ru' => 'Печенье'], 'slug' => 'galletas'],
                             ['name' => ['es' => 'Pasteles', 'en' => 'Cakes', 'pt' => 'Bolos', 'fr' => 'Gâteaux', 'he' => 'עוגות', 'ru' => 'Торты'], 'slug' => 'pasteles'],
                             ['name' => ['es' => 'Masas', 'en' => 'Dough', 'pt' => 'Massas', 'fr' => 'Pâtes', 'he' => 'בצקים', 'ru' => 'Тесто'], 'slug' => 'masas'],
                        ]
                    ],
                    [
                        'name' => [
                            'es' => 'Bebidas', 
                            'en' => 'Beverages', 
                            'pt' => 'Bebidas', 
                            'fr' => 'Boissons', 
                            'he' => 'משקאות', 
                            'ru' => 'Напитки'
                        ],
                        'slug' => 'bebidas',
                        'children' => [
                            ['name' => ['es' => 'Jugos', 'en' => 'Juices', 'pt' => 'Sucos', 'fr' => 'Jus', 'he' => 'מיצים', 'ru' => 'Соки'], 'slug' => 'jugos'],
                            ['name' => ['es' => 'Gaseosas', 'en' => 'Sodas', 'pt' => 'Refrigerantes', 'fr' => 'Sodas', 'he' => 'משקאות מוגזים', 'ru' => 'Газировка'], 'slug' => 'gaseosas'],
                            ['name' => ['es' => 'Vinos y Licores', 'en' => 'Wines & Spirits', 'pt' => 'Vinhos e Destilados', 'fr' => 'Vins et Spiritueux', 'he' => 'יינות ומשקאות חריפים', 'ru' => 'Вина и спиртные напитки'], 'slug' => 'vinos'],
                            ['name' => ['es' => 'Café y Té', 'en' => 'Coffee & Tea', 'pt' => 'Café e Chá', 'fr' => 'Café et Thé', 'he' => 'קפה ותה', 'ru' => 'Кофе и чай'], 'slug' => 'cafe-te'],
                        ]
                    ],
                    [
                        'name' => [
                            'es' => 'Snacks y Golosinas', 
                            'en' => 'Snacks & Candy', 
                            'pt' => 'Snacks e Doces', 
                            'fr' => 'Collations et bonbons', 
                            'he' => 'חטיפים וממתקים', 
                            'ru' => 'Закуски и сладости'
                        ],
                        'slug' => 'snacks',
                        'children' => [
                            ['name' => ['es' => 'Papas Fritas', 'en' => 'Chips', 'pt' => 'Batata Frita', 'fr' => 'Chips', 'he' => 'צ\'יפס', 'ru' => 'Чипсы'], 'slug' => 'papas-fritas'],
                            ['name' => ['es' => 'Chocolates', 'en' => 'Chocolates', 'pt' => 'Chocolates', 'fr' => 'Chocolats', 'he' => 'שוקולדים', 'ru' => 'Шоколад'], 'slug' => 'chocolates'],
                            ['name' => ['es' => 'Caramelos', 'en' => 'Candy', 'pt' => 'Balas', 'fr' => 'Bonbons', 'he' => 'סוכריות', 'ru' => 'Конфеты'], 'slug' => 'caramelos'],
                        ]
                    ],
                    [
                        'name' => [
                            'es' => 'Despensa', 
                            'en' => 'Pantry', 
                            'pt' => 'Despensa', 
                            'fr' => 'Garde-manger', 
                            'he' => 'מזווה', 
                            'ru' => 'Кладовая'
                        ],
                        'slug' => 'despensa',
                        'children' => [
                            ['name' => ['es' => 'Condimentos', 'en' => 'Condiments', 'pt' => 'Condimentos', 'fr' => 'Condiments', 'he' => 'תבלינים', 'ru' => 'Приправы'], 'slug' => 'condimentos'],
                            ['name' => ['es' => 'Aceites', 'en' => 'Oils', 'pt' => 'Óleos', 'fr' => 'Huiles', 'he' => 'שמנים', 'ru' => 'Масла'], 'slug' => 'aceites'],
                            ['name' => ['es' => 'Pastas', 'en' => 'Pasta', 'pt' => 'Massas', 'fr' => 'Pâtes', 'he' => 'פסטות', 'ru' => 'Макароны'], 'slug' => 'pastas'],
                            ['name' => ['es' => 'Arroz y Legumbres', 'en' => 'Rice & Legumes', 'pt' => 'Arroz e Leguminosas', 'fr' => 'Riz et Légumineuses', 'he' => 'אורז וקטניות', 'ru' => 'Рис и бобовые'], 'slug' => 'arroz-legumbres'],
                            ['name' => ['es' => 'Conservas', 'en' => 'Canned Food', 'pt' => 'Conservas', 'fr' => 'Conserves', 'he' => 'שימורים', 'ru' => 'Консервы'], 'slug' => 'conservas'],
                            ['name' => ['es' => 'Salsas', 'en' => 'Sauces', 'pt' => 'Molhos', 'fr' => 'Sauces', 'he' => 'רטבים', 'ru' => 'Соусы'], 'slug' => 'salsas'],
                        ]
                    ],
                    [
                        'name' => [
                            'es' => 'Frutas y Verduras', 
                            'en' => 'Fruits & Vegetables', 
                            'pt' => 'Frutas e Legumes', 
                            'fr' => 'Fruits et Légumes', 
                            'he' => 'פירות וירקות', 
                            'ru' => 'Фрукты и овощи'
                        ],
                        'slug' => 'frutas-verduras',
                    ],
                    [
                        'name' => [
                            'es' => 'Comidas Preparadas', 
                            'en' => 'Prepared Meals', 
                            'pt' => 'Refeições Prontas', 
                            'fr' => 'Plats préparés', 
                            'he' => 'ארוחות מוכנות', 
                            'ru' => 'Готовые блюда'
                        ],
                        'slug' => 'comidas-preparadas',
                    ]
                ]
            ]
        ];

        // 2. Recursive Creation
        $this->createCategories($categories);
    }

    private function createCategories(array $categories, $parentId = null)
    {
        foreach ($categories as $data) {
            $children = $data['children'] ?? [];
            unset($data['children']);

            $data['parent_id'] = $parentId;
            
            // Encode name to JSON
            $data['name'] = json_encode($data['name']);

            $category = Category::updateOrCreate(
                ['slug' => $data['slug']],
                $data
            );

            if (!empty($children)) {
                $this->createCategories($children, $category->id);
            }
        }
    }
}
