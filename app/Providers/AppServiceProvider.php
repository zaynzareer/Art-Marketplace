<?php

namespace App\Providers;

use App\Models\CartItem;
use App\Models\Order;
use App\Models\Product;
use App\Observers\CartItemObserver;
use App\Observers\OrderObserver;
use App\Observers\ProductObserver;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
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

        // Register API rate limiters
        $this->configureRateLimiting();
    }

    /**
     * Configure the rate limiters for the application.
     */
    protected function configureRateLimiting(): void
    {
        // High security - Checkout and payment operations
        RateLimiter::for('checkout', function (Request $request) {
            return Limit::perMinute(10)->by($request->user()?->id ?: $request->ip())
                ->response(function (Request $request, array $headers) {
                    return response()->json([
                        'message' => 'Too many checkout attempts. Please try again later.'
                    ], 429, $headers);
                });
        });

        // Medium security - Product write operations (seller)
        RateLimiter::for('products-write', function (Request $request) {
            return Limit::perMinute(20)->by($request->user()?->id ?: $request->ip())
                ->response(function (Request $request, array $headers) {
                    return response()->json([
                        'message' => 'Too many product modifications. Please slow down.'
                    ], 429, $headers);
                });
        });

        // Medium security - Order status updates (seller)
        RateLimiter::for('orders-update', function (Request $request) {
            return Limit::perMinute(30)->by($request->user()?->id ?: $request->ip())
                ->response(function (Request $request, array $headers) {
                    return response()->json([
                        'message' => 'Too many order updates. Please slow down.'
                    ], 429, $headers);
                });
        });

        // Medium security - Cart operations
        RateLimiter::for('cart', function (Request $request) {
            return Limit::perMinute(60)->by($request->user()?->id ?: $request->ip())
                ->response(function (Request $request, array $headers) {
                    return response()->json([
                        'message' => 'Too many cart operations. Please wait a moment.'
                    ], 429, $headers);
                });
        });

        // Medium security - Dashboard/metrics access
        RateLimiter::for('dashboard', function (Request $request) {
            return Limit::perMinute(60)->by($request->user()?->id ?: $request->ip())
                ->response(function (Request $request, array $headers) {
                    return response()->json([
                        'message' => 'Too many dashboard requests. Please wait a moment.'
                    ], 429, $headers);
                });
        });

        // Lower security - Read operations (product listings, order viewing)
        RateLimiter::for('api-read', function (Request $request) {
            return Limit::perMinute(100)->by($request->user()?->id ?: $request->ip())
                ->response(function (Request $request, array $headers) {
                    return response()->json([
                        'message' => 'Too many requests. Please slow down.'
                    ], 429, $headers);
                });
        });

        // General API rate limit (fallback)
        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(120)->by($request->user()?->id ?: $request->ip())
                ->response(function (Request $request, array $headers) {
                    return response()->json([
                        'message' => 'Too many API requests. Please try again later.'
                    ], 429, $headers);
                });
        });
    }
}
