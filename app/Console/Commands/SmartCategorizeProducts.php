<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Support\Str;

class SmartCategorizeProducts extends Command
{
    protected $signature = 'products:smart-categorize {--reset : Reset all categories first}';
    protected $description = 'Assign categories to products based on keywords';

    public function handle()
    {
        $reset = $this->option('reset');

        if ($reset) {
            $this->info('Resetting product categories...');
            Product::query()->update(['category_id' => null]);
        }

        $categories = Category::with('children')->get();
        $flattenedCategories = $this->flattenCategories($categories);

        $this->info('Starting smart categorization...');
        
        $count = 0;
        $products = Product::whereNull('category_id')->get();
        $bar = $this->output->createProgressBar(count($products));

        foreach ($products as $product) {
            $text = Str::lower($product->name . ' ' . ($product->description ?? ''));
            
            $matchedCategoryId = $this->findCategory($text, $flattenedCategories);

            if ($matchedCategoryId) {
                $product->update(['category_id' => $matchedCategoryId]);
                $count++;
            }
            
            $bar->advance();
        }

        $bar->finish();
        $this->newLine();
        $this->info("Categorized $count products.");
    }

    private function flattenCategories($categories)
    {
        $flat = [];
        foreach ($categories as $cat) {
            // Add self
            $flat[] = [
                'id' => $cat->id,
                'slug' => $cat->slug,
                'keywords' => $this->getKeywordsForSlug($cat->slug)
            ];
            
            // Add children
            if ($cat->children) {
                foreach ($cat->children as $child) {
                    $flat[] = [
                        'id' => $child->id,
                        'slug' => $child->slug,
                        'keywords' => $this->getKeywordsForSlug($child->slug)
                    ];
                }
            }
        }
        return $flat;
    }

    private function findCategory($text, $flatCategories)
    {
        // Prioritize specific matches (subcategories often have more specific keywords)
        // For now, simple iteration.
        
        foreach ($flatCategories as $cat) {
            foreach ($cat['keywords'] as $keyword) {
                if (str_contains($text, $keyword)) {
                    return $cat['id'];
                }
            }
        }
        return null;
    }

    private function getKeywordsForSlug($slug)
    {
        $map = [
            'vinos' => ['wine', 'vino', 'merlot', 'cabernet', 'malbec', 'chardonnay', 'sauvignon', 'champagne', 'licor', 'spirit', 'vodka', 'whisky', 'beer', 'cerveza', 'cocktail', 'margarita'],
            'quesos' => ['cheese', 'queso', 'cheddar', 'mozzarella', 'parmesan', 'brie', 'camembert'],
            'leche' => ['milk', 'leche', 'lactose'],
            'yogurt' => ['yogurt', 'yogur'],
            'mantequilla' => ['butter', 'mantequilla', 'margarine'],
            'helados' => ['ice cream', 'helado', 'sorbet'],
            'vacuno' => ['beef', 'vacuno', 'carne', 'steak', 'burger', 'hamburguesa'],
            'pollo' => ['chicken', 'pollo', 'turkey', 'pavo'],
            'embutidos' => ['salami', 'sausage', 'salchicha', 'jamon', 'ham', 'pepperoni', 'frankfurter', 'hot dog', 'cold cut'],
            'atun' => ['tuna', 'atun'],
            'salmon' => ['salmon', 'salmón'],
            'conservas-pescado' => ['sardine', 'sardina', 'anchovy', 'anchoa', 'herring', 'arenque', 'mackerel', 'caballa', 'fish', 'pescado'],
            'pescados' => ['fish', 'pescado', 'tilapia', 'cod', 'bacalao'],
            'pan' => ['bread', 'pan', 'baguette', 'loaf', 'bun', 'roll', 'challah', 'pita', 'bagel'],
            'galletas' => ['cookie', 'galleta', 'biscuit', 'cracker'],
            'pasteles' => ['cake', 'pastel', 'torta', 'muffin', 'cupcake', 'brownie', 'pastry'],
            'masas' => ['dough', 'masa', 'crust'],
            'jugos' => ['juice', 'jugo', 'nectar', 'lemonade', 'orangeade'],
            'gaseosas' => ['soda', 'cola', 'sprite', 'fanta', 'pepsi', 'coca', 'beverage', 'drink', 'refresco'],
            'cafe-te' => ['coffee', 'cafe', 'tea', 'té', 'espresso', 'latte'],
            'papas-fritas' => ['chip', 'papa frita', 'crisp', 'potato'],
            'chocolates' => ['chocolate', 'cacao', 'cocoa', 'truffle', 'bonbon'],
            'caramelos' => ['candy', 'caramelo', 'sweet', 'gummy', 'lollipop'],
            'snacks' => ['snack', 'popcorn', 'pretzel', 'nut', 'trail mix'],
            'condimentos' => ['spice', 'especias', 'salt', 'sal', 'pepper', 'pimienta', 'seasoning', 'condimento', 'vinegar', 'vinagre'],
            'aceites' => ['oil', 'aceite', 'olive', 'oliva', 'canola', 'sunflower', 'girasol'],
            'pastas' => ['pasta', 'spaghetti', 'noodle', 'macaroni', 'fideo', 'tallarin', 'lasagna'],
            'arroz-legumbres' => ['rice', 'arroz', 'bean', 'frijol', 'lentil', 'lenteja', 'chickpea', 'garbanzo', 'quinoa'],
            'conservas' => ['canned', 'conserva', 'pickle', 'corn', 'choclo', 'pea', 'arveja', 'tomato paste', 'salsa de tomate'],
            'salsas' => ['sauce', 'salsa', 'ketchup', 'mustard', 'mostaza', 'mayo', 'mayonnaise'],
            'frutas-verduras' => ['fruit', 'fruta', 'vegetable', 'verdura', 'apple', 'manzana', 'banana', 'orange', 'naranja', 'lettuce', 'lechuga', 'tomato', 'tomate'],
            'comidas-preparadas' => ['meal', 'comida', 'dinner', 'lunch', 'salad', 'ensalada', 'soup', 'sopa', 'pizza', 'wrap', 'sandwich', 'platter'],
        ];

        return $map[$slug] ?? [];
    }
}
