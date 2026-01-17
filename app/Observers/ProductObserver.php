<?php

namespace App\Observers;

use App\Models\Product;
use App\Services\CacheService;

class ProductObserver
{
    /**
     * Handle the Product "created" event.
     */
    public function created(Product $product): void
    {
        CacheService::invalidateProducts();
        CacheService::invalidateSeller($product->seller_id);
    }

    /**
     * Handle the Product "updated" event.
     */
    public function updated(Product $product): void
    {
        CacheService::invalidateProduct($product->id);
        CacheService::invalidateProducts();
        CacheService::invalidateSeller($product->seller_id);
    }

    /**
     * Handle the Product "deleted" event.
     */
    public function deleted(Product $product): void
    {
        CacheService::invalidateProduct($product->id);
        CacheService::invalidateProducts();
        CacheService::invalidateSeller($product->seller_id);
    }

    /**
     * Handle the Product "restored" event.
     */
    public function restored(Product $product): void
    {
        CacheService::invalidateProducts();
        CacheService::invalidateSeller($product->seller_id);
    }

    /**
     * Handle the Product "force deleted" event.
     */
    public function forceDeleted(Product $product): void
    {
        CacheService::invalidateProduct($product->id);
        CacheService::invalidateProducts();
        CacheService::invalidateSeller($product->seller_id);
    }
}
