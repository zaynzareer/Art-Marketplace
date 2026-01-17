<?php

namespace App\Observers;

use App\Models\CartItem;
use App\Services\CacheService;

class CartItemObserver
{
    /**
     * Handle the CartItem "created" event.
     */
    public function created(CartItem $cartItem): void
    {
        $buyerId = $cartItem->cart->user_id;
        CacheService::invalidateCart($buyerId);
    }

    /**
     * Handle the CartItem "updated" event.
     */
    public function updated(CartItem $cartItem): void
    {
        $buyerId = $cartItem->cart->user_id;
        CacheService::invalidateCart($buyerId);
    }

    /**
     * Handle the CartItem "deleted" event.
     */
    public function deleted(CartItem $cartItem): void
    {
        $buyerId = $cartItem->cart->user_id;
        CacheService::invalidateCart($buyerId);
    }

    /**
     * Handle the CartItem "restored" event.
     */
    public function restored(CartItem $cartItem): void
    {
        $buyerId = $cartItem->cart->user_id;
        CacheService::invalidateCart($buyerId);
    }

    /**
     * Handle the CartItem "force deleted" event.
     */
    public function forceDeleted(CartItem $cartItem): void
    {
        $buyerId = $cartItem->cart->user_id;
        CacheService::invalidateCart($buyerId);
    }
}
