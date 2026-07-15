<?php

namespace App\Console\Commands;

use App\Models\Product;
use Illuminate\Console\Command;

class CleanProductData extends Command
{
    protected $signature = 'products:clean-data {--dry-run : Solo mostrar qué se haría, sin escribir en la base}';

    protected $description = 'Limpia datos de scraping en productos: despublica los NOPUBLICAR, saca reseñas falsas, notas internas y nombres tipo lista';

    public function handle(): void
    {
        $dryRun = (bool) $this->option('dry-run');
        $this->info($dryRun ? '=== DRY RUN (no se escribe nada) ===' : '=== APLICANDO CAMBIOS ===');

        // 1) Despublicar productos delistados por la certificadora (NOPUBLICAR filtrado desde el scraping)
        $nopublicar = Product::where('is_active', true)->where('description', 'LIKE', '%NOPUBLICAR%');
        $nopublicarCount = $nopublicar->count();
        $this->info("1) Productos a despublicar (NOPUBLICAR): {$nopublicarCount}");
        if (!$dryRun) {
            $nopublicar->update(['is_active' => false]);
        }

        // 2) Nombres tipo lista de variantes (3+ comas): el nombre pasa a ser el primer ítem,
        //    y el texto completo original se preserva en description.
        $listNameCandidates = Product::active()
            ->whereRaw("(LENGTH(name) - LENGTH(REPLACE(name, ',', ''))) >= 3")
            ->get();

        $fixed = 0;
        $skipped = 0;
        foreach ($listNameCandidates as $product) {
            $first = trim(explode(',', $product->name)[0]);
            $len = mb_strlen($first);

            if ($len < 6 || $len > 90) {
                $skipped++;
                $this->line("   SKIP (revisar a mano) #{$product->id}: " . mb_substr($product->name, 0, 70));
                continue;
            }

            $fixed++;
            if (!$dryRun) {
                $product->description = $product->name;
                $product->name = $first;
                $product->save();
            }
        }
        $this->info("2) Nombres-lista corregidos: {$fixed} (guardando el texto completo en description), saltados para revisión manual: {$skipped}");

        // Para el resto de pasos, excluimos los productos ya tocados en el paso 2 (por id)
        $touchedIds = $listNameCandidates->pluck('id')->diff(
            $listNameCandidates->filter(function ($p) {
                $first = trim(explode(',', $p->name)[0]);
                $len = mb_strlen($first);
                return $len < 6 || $len > 90;
            })->pluck('id')
        );

        // 3) Reseñas / notas rabínicas inventadas por HumanValueLayerService
        $fakeReviews = Product::active()
            ->whereNotIn('id', $touchedIds)
            ->where('description', 'LIKE', '%CONTENIDO EXCLUSIVO KOSHER%');
        $fakeReviewsCount = $fakeReviews->count();
        $this->info("3) Reseñas falsas a vaciar: {$fakeReviewsCount}");
        if (!$dryRun) {
            $fakeReviews->update(['description' => null]);
        }

        // 4) "Rubro: ..." (metadata interna). Si tiene "Sin TACC" rescatamos ese dato real.
        $rubro = Product::active()
            ->whereNotIn('id', $touchedIds)
            ->where('description', 'LIKE', '%Rubro:%')
            ->get();

        $rubroConTacc = 0;
        $rubroVaciados = 0;
        foreach ($rubro as $product) {
            if (stripos($product->description, 'Sin TACC') !== false) {
                $rubroConTacc++;
                if (!$dryRun) {
                    $product->description = 'Sin gluten (sin TACC).';
                    $product->save();
                }
            } else {
                $rubroVaciados++;
                if (!$dryRun) {
                    $product->description = null;
                    $product->save();
                }
            }
        }
        $this->info("4) 'Rubro:' con Sin TACC rescatados: {$rubroConTacc}, vaciados sin dato útil: {$rubroVaciados}");

        // 5) Descripción que solo repite el tipo kosher (redundante con el badge de la página)
        $words = ['parve', 'pareve', 'dairy', 'meat', 'fish', 'lacteo', 'lácteo', 'carnico', 'cárnico', 'kosher', 'certified', 'unknown'];
        $redundant = Product::active()
            ->whereNotIn('id', $touchedIds)
            ->whereNotNull('description')
            ->where('description', '!=', '')
            ->where(function ($q) use ($words) {
                foreach ($words as $w) {
                    $q->orWhereRaw('LOWER(TRIM(description)) = ?', [$w]);
                }
            });
        $redundantCount = $redundant->count();
        $this->info("5) Descripciones redundantes (solo tipo kosher) a vaciar: {$redundantCount}");
        if (!$dryRun) {
            $redundant->update(['description' => null]);
        }

        $this->newLine();
        $this->info($dryRun ? 'Dry run terminado. Corré sin --dry-run para aplicar.' : 'Cambios aplicados.');
    }
}
