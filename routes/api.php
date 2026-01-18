<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\SellerDashboardController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::middleware(['auth:sanctum'])->group(function () {
    Route::middleware('role:seller')->group(function () {
        // Product write operations - 20 requests/minute
        Route::apiResource('products', ProductController::class)
            ->only(['store', 'update', 'destroy'])
            ->middleware('throttle:products-write')
            ->names([
                'store'   => 'api.products.store',
                'update'  => 'api.products.update',
                'destroy' => 'api.products.destroy',
            ]);

        // Dashboard metrics - 60 requests/minute
        Route::get('seller/dashboard', [SellerDashboardController::class, 'index'])
            ->middleware('throttle:dashboard')
            ->name('api.seller.dashboard');
            
        // Seller product listing - 60 requests/minute
        Route::get('products/sellerIndex', [ProductController::class, 'sellerIndex'])
            ->middleware('throttle:dashboard')
            ->name('api.products.sellerIndex');
        
        // Seller orders listing - 100 requests/minute
        Route::get('seller/orders', [OrderController::class, 'sellerIndex'])
            ->middleware('throttle:api-read')
            ->name('api.orders.sellerIndex');
            
        // Order status updates - 30 requests/minute
        Route::post('orders/{orderId}/update', [OrderController::class, 'update'])
            ->middleware('throttle:orders-update')
            ->name('api.orders.update');
        
    });

    Route::middleware('role:buyer')->group(function () {
        // Product browsing - 100 requests/minute
        Route::apiResource('products', ProductController::class)
            ->only(['index'])
            ->middleware('throttle:api-read')
            ->names([
                'index' => 'api.products.index'
            ]);

        // Cart operations - 60 requests/minute
        Route::apiResource('cart', CartController::class)
            ->only(['show', 'store', 'update', 'destroy'])
            ->middleware('throttle:cart')
            ->names([
                'show'   => 'api.cart.show',
                'store'   => 'api.cart.store',
                'update'  => 'api.cart.update',
                'destroy' => 'api.cart.destroy',
            ]);

        // Order viewing - 100 requests/minute
        Route::get('orders', [OrderController::class, 'index'])
            ->middleware('throttle:api-read')
            ->name('api.orders.index');
        
        // Checkout operations - 10 requests/minute (strict)
        Route::post('checkout', [CheckoutController::class, 'stripeCheckout'])
            ->middleware('throttle:checkout')
            ->name('api.checkout.process');
    });

    // Both roles can access - 100 requests/minute
    Route::apiResource('products', ProductController::class)
        ->only(['show'])
        ->middleware('throttle:api-read')
        ->names([
            'show' => 'api.products.show'
        ]);
});
