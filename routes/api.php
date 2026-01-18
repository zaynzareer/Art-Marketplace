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
        // Prefix all API route names with 'api.' for easier identification
        Route::apiResource('products', ProductController::class)
            ->only(['store', 'update', 'destroy'])
            ->names([
                'store'   => 'api.products.store',
                'update'  => 'api.products.update',
                'destroy' => 'api.products.destroy',
            ]);

        Route::get('seller/dashboard', [SellerDashboardController::class, 'index'])
            ->name('api.seller.dashboard');
            
        Route::get('products/sellerIndex', [ProductController::class, 'sellerIndex'])
            ->name('api.products.sellerIndex');
        
        Route::get('seller/orders', [OrderController::class, 'sellerIndex'])
            ->name('api.orders.sellerIndex');
            
        Route::post('orders/{orderId}/update', [OrderController::class, 'update'])
            ->name('api.orders.update');
        
    });

    Route::middleware('role:buyer')->group(function () {
        Route::apiResource('products', ProductController::class)
            ->only(['index'])
            ->names([
                'index' => 'api.products.index'
            ]);

        Route::apiResource('cart', CartController::class)
            ->only(['show', 'store', 'update', 'destroy'])
            ->names([
                'show'   => 'api.cart.show',
                'store'   => 'api.cart.store',
                'update'  => 'api.cart.update',
                'destroy' => 'api.cart.destroy',
            ]);

        Route::apiResource('orders', OrderController::class)
            ->only(['index'])
            ->names([
                'index' => 'api.orders.index',
            ]);
        
        Route::post('checkout', [CheckoutController::class, 'stripeCheckout'])
            ->name('api.checkout.process');
    });

    // Both roles can access this route
    Route::apiResource('products', ProductController::class)
        ->only(['show'])
        ->names([
            'show' => 'api.products.show'
        ]);
});
