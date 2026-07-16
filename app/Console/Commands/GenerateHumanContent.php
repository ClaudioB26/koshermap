<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\HumanValueLayerService;
use App\Models\Product;

class GenerateHumanContent extends Command
{
    protected $signature = 'human:generate {--limit=50 : Límite de productos a procesar} {--all : Procesar todos los productos}';
    protected $description = 'Generar contenido humano único para productos kosher';

    public function handle()
    {
        // Deshabilitado a propósito (julio 2026): este comando escribía reseñas de
        // "expertos" y "retroalimentación comunitaria" inventadas, iguales para
        // cualquier producto, en el campo description. Google lo detectó como
        // contenido de bajo valor / reseñas fabricadas y bloqueó la aprobación de
        // AdSense. Ya se limpiaron ~420 productos afectados en producción.
        // No lo reactives sin antes cambiar la lógica para que use información
        // real (no plantillas fijas de HumanValueLayerService).
        $this->error('Comando deshabilitado: generaba reseñas falsas detectadas por Google como contenido de bajo valor. Ver comentario en el código para más detalle.');
        return;

        $this->info('=== GENERADOR DE CONTENIDO HUMANO ===');
        
        $humanService = new HumanValueLayerService();
        
        if ($this->option('all')) {
            $limit = Product::count();
            $this->info("Procesando TODOS los productos ({$limit})...");
        } else {
            $limit = $this->option('limit');
            $this->info("Procesando {$limit} productos...");
        }
        
        $products = Product::whereNull('description')
            ->orWhere('description', 'LIKE', '%Producto importado%')
            ->limit($limit)
            ->get();
        
        $this->info("Encontrados {$products->count()} productos para procesar");
        
        if ($products->count() === 0) {
            $this->info('No hay productos que necesiten contenido humano.');
            return;
        }
        
        $progressBar = $this->output->createProgressBar($products->count());
        $progressBar->start();
        
        $generated = 0;
        
        foreach ($products as $product) {
            try {
                $humanService->saveHumanContent($product);
                $generated++;
                $progressBar->advance();
                
                if ($generated % 10 === 0) {
                    $this->line("\n Generados: {$generated}/{$products->count()}");
                }
            } catch (\Exception $e) {
                $this->error("\nError procesando producto {$product->name}: " . $e->getMessage());
            }
        }
        
        $progressBar->finish();
        
        $this->newLine();
        $this->info("=== RESUMEN ===");
        $this->info("Productos procesados: {$products->count()}");
        $this->info("Contenido generado: {$generated}");
        $this->info("Éxito: " . round(($generated / $products->count()) * 100, 2) . "%");
        
        // Estadísticas finales
        $totalProducts = Product::count();
        $withContent = Product::whereNotNull('description')
            ->where('description', 'NOT LIKE', '%Producto importado%')
            ->count();
        
        $this->info("\n=== ESTADÍSTICAS FINALES ===");
        $this->info("Total productos: {$totalProducts}");
        $this->info("Con contenido humano: {$withContent}");
        $this->info("Porcentaje completado: " . round(($withContent / $totalProducts) * 100, 2) . "%");
        
        $this->info('¡Contenido humano generado exitosamente!');
    }
}
