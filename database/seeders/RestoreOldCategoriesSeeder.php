<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;
use Illuminate\Support\Facades\DB;

class RestoreOldCategoriesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        // Categorías originales basadas en el análisis anterior
        $oldCategories = [
            'Chocolates',
            '. Lista de Pesaj 2025NOPUBLICAR',
            'Productos naturistas y varios',
            'Especias , condimentos en polvo y sales',
            'Mermeladas',
            'Productos de copetin',
            'Tomates en lata, en tetrabrick, salsas y pure',
            'Fideos',
            'Heladerias',
            'Cereales',
            'Aceites y Grasas vegetales',
            'Pan',
            'Galletitas y bizcochos',
            'Aderezos',
            'Cacao en polvo y preparados para leche',
            'Papas pre-fritas',
            'Yerba Mate',
            'Chocolates para taza',
            'Ketchup',
            'Legumbres',
            'Alimentos para Bebes',
            'Quesos no lácteos',
            'Chicles',
            'Crema láctea',
            'Articulos para la limpieza de vajilla',
            'Mostaza',
            'Pan rallado y rebozadores',
            'Hamburguesas vegetales',
            'Margarina',
            'Polenta',
            'Vinagre',
            'Quesos',
            'Helados en pinta',
            'Leche chocolatada',
            'Polvo para preparar jugos',
            'Caldos y sopas en Polvo',
            'Conitos de dulce de leche',
            'Polvo para preparar postres',
            'Polvos para preparar tortas y budines',
            'Levadura',
            'Alfajor de arroz',
            'Milanesas , rebozados y productos vegetales',
            'Milanesas de soja',
            'Sidras y afines',
            'Alimentos liquidos de soja y frutales',
            'Cerveza',
            'Pure de papa',
            'Crema no lactea y saborizante de cafe',
            'Algas para sushi',
            'Articulos de cocina',
            'Masa Fila',
            'Pescado ahumado',
            'Polvo para preparar flanes',
            'Postres no lacteos',
            'Rollos y bandejas de alumino y material de cocina',
            'Gelatinas',
            'Medicamentos',
            'Pastas frescas',
            'Pescados Frescos',
            'Arroz Saborizado',
            'Avena',
            'Ensaladas de frutas en lata',
            'Salsa de soja',
            'Untables vegetales',
            'Gaseosas',
            'Alimentos para bebes',
            'Bebidas',
            'Lacteos',
            'Carnes',
            'Verduras',
            'Frutas',
            'Snacks',
            'Condimentos',
            'Dulces',
            'Postres',
            'Panaderia',
            'Cereales y granos',
            'Bebidas alcoholicas',
            'Productos de limpieza',
            'Articulos de cocina',
            'Salud y bienestar',
            'Congelados',
            'Desayuno',
            'Merienda',
            'Cocina',
            'Hogar',
        ];

        // Limpiar categorías actuales
        DB::table('categories')->delete();

        // Crear categorías antiguas
        foreach ($oldCategories as $categoryName) {
            $slug = \Illuminate\Support\Str::slug($categoryName);
            
            // Asegurar slug único
            $originalSlug = $slug;
            $counter = 1;
            while (Category::where('slug', $slug)->exists()) {
                $slug = $originalSlug . '-' . $counter;
                $counter++;
            }

            Category::create([
                'name' => $categoryName,
                'slug' => $slug,
            ]);
        }

        $this->command->info('Categorías antiguas restauradas exitosamente');
        $this->command->info('Total categorías restauradas: ' . count($oldCategories));
    }
}
