<?php

namespace App\Console\Commands\Concerns;

use App\Models\Product;

trait TracksProductActivity
{
    private array $seenProductIds = [];

    private function markProductSeen(Product $product): void
    {
        $this->seenProductIds[] = $product->id;
    }

    /**
     * Marca como inactivos los productos de esta certificadora que no
     * aparecieron en la corrida actual (dejaron de estar en la lista del certificador).
     */
    private function deactivateStaleProducts(int $certifierId): int
    {
        return Product::where('certifier_id', $certifierId)
            ->where('is_active', true)
            ->whereNotIn('id', $this->seenProductIds ?: [0])
            ->update(['is_active' => false]);
    }
}
