<?php

namespace App\Observers;

use App\Models\Order;
use App\Services\CacheService;

class OrderObserver
{
    /**
     * Handle the Order "created" event.
     */
    public function created(Order $order): void
    {
        // Invalidate buyer's cart and orders
        CacheService::invalidateCart($order->buyer_id);
        CacheService::invalidateBuyerOrders($order->buyer_id);
        
        // Invalidate seller's metrics and orders
        CacheService::invalidateSeller($order->seller_id);
        CacheService::invalidateSellerOrders($order->seller_id);
    }

    /**
     * Handle the Order "updated" event.
     */
    public function updated(Order $order): void
    {
        // Invalidate seller's metrics when status changes
        CacheService::invalidateSeller($order->seller_id);
        CacheService::invalidateSellerOrders($order->seller_id);
    }

    /**
     * Handle the Order "deleted" event.
     */
    public function deleted(Order $order): void
    {
        CacheService::invalidateBuyerOrders($order->buyer_id);
        CacheService::invalidateSellerOrders($order->seller_id);
    }

    /**
     * Handle the Order "restored" event.
     */
    public function restored(Order $order): void
    {
        CacheService::invalidateBuyerOrders($order->buyer_id);
        CacheService::invalidateSellerOrders($order->seller_id);
    }

    /**
     * Handle the Order "force deleted" event.
     */
    public function forceDeleted(Order $order): void
    {
        CacheService::invalidateBuyerOrders($order->buyer_id);
        CacheService::invalidateSellerOrders($order->seller_id);
    }
}
