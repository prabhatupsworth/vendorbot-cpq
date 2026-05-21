<?php

namespace Modules\Product\Observers;

use Illuminate\Support\Facades\Cache;
use Modules\Product\Models\Product;

class ProductObserver
{
    /**
     * Handle product created event
     */
    public function created(Product $product): void
    {
        $this->clearCache();
    }

    /**
     * Handle product updated event
     */
    public function updated(Product $product): void
    {
        $this->clearCache();
    }

    /**
     * Handle product deleted event
     */
    public function deleted(Product $product): void
    {
        $this->clearCache();
    }

    /**
     * Handle product restored event
     */
    public function restored(Product $product): void
    {
        $this->clearCache();
    }

    /**
     * Clear product cache
     */
    private function clearCache(): void
    {
        Cache::forget('products.index');

        Cache::tags([
            'products'
        ])->flush();
    }
}
