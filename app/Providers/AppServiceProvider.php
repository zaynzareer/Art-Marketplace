<?php

namespace App\Providers;

use App\Models\CartItem;
use App\Models\Order;
use App\Models\Product;
use App\Observers\CartItemObserver;
use App\Observers\OrderObserver;
use App\Observers\ProductObserver;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Register model observers for cache invalidation
        Product::observe(ProductObserver::class);
        CartItem::observe(CartItemObserver::class);
        Order::observe(OrderObserver::class);
    }
}
