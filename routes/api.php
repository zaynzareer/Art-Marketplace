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


/**
 * Scope-based Access Control for API Routes:
 * Uses Sanctum's tokenCan() method to validate scopes on protected endpoints
 * 
 * Each route group enforces specific token scopes:
 * - Sellers can: create/update/delete products, update orders, view dashboard
 * - Buyers can: read products, manage cart, view orders, checkout
 */
Route::middleware(['auth:sanctum'])->group(function () {
    Route::middleware('role:seller')->group(function () {
        // Product write operations - 20 requests/minute
        // Requires: products:create, products:update, or products:delete scope
        Route::apiResource('products', ProductController::class)
            ->only(['store', 'update', 'destroy'])
            ->middleware('throttle:products-write')
            ->names([
                'store'   => 'api.products.store',
                'update'  => 'api.products.update',
                'destroy' => 'api.products.destroy',
            ]);

        // Dashboard metrics - 60 requests/minute
        // Requires: dashboard:read scope
        Route::get('seller/dashboard', [SellerDashboardController::class, 'index'])
            ->middleware('throttle:dashboard')
            ->name('api.seller.dashboard');
            
        // Seller product listing - 60 requests/minute
        // Requires: products:read scope
        Route::get('products/sellerIndex', [ProductController::class, 'sellerIndex'])
            ->middleware('throttle:dashboard')
            ->name('api.products.sellerIndex');

        // Seller product detail (edit context) - 60 requests/minute
        // Requires: products:update scope
        Route::get('products/{id}/seller', [ProductController::class, 'sellerShow'])
            ->middleware('throttle:api-read')
            ->name('api.products.seller.show');
        
        // Seller orders listing - 100 requests/minute
        // Requires: orders:read scope
        Route::get('seller/orders', [OrderController::class, 'sellerIndex'])
            ->middleware('throttle:api-read')
            ->name('api.orders.sellerIndex');
            
        // Order status updates - 30 requests/minute
        // Requires: orders:update-status scope
        Route::post('orders/{orderId}/update', [OrderController::class, 'update'])
            ->middleware('throttle:orders-update')
            ->name('api.orders.update');
        
    });

    Route::middleware('role:buyer')->group(function () {
        // Product browsing - 100 requests/minute
        // Requires: products:read scope
        Route::apiResource('products', ProductController::class)
            ->only(['index'])
            ->middleware('throttle:api-read')
            ->names([
                'index' => 'api.products.index'
            ]);

        // Cart operations - 60 requests/minute
        // Requires: cart:read and/or cart:write scopes
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
        // Requires: orders:read scope
        Route::get('orders', [OrderController::class, 'index'])
            ->middleware('throttle:api-read')
            ->name('api.orders.index');
        
        // Checkout operations - 10 requests/minute (strict)
        // Requires: checkout:process scope
        Route::post('checkout', [CheckoutController::class, 'stripeCheckout'])
            ->middleware('throttle:checkout')
            ->name('api.checkout.process');
    });

    // Both roles can access - 100 requests/minute
    // Requires: products:read scope
    Route::apiResource('products', ProductController::class)
        ->only(['show'])
        ->middleware('throttle:api-read')
        ->names([
            'show' => 'api.products.show'
        ]);
});
