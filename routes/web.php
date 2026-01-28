<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\Auth\GoogleAuthController;
use App\Http\Controllers\Auth\OAuthRegistrationController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/about-us', function () {
    return view('about-us');
})->name('about');

// Contact Us Routes
Route::get('/contact-us', [ContactController::class, 'show'])->name('contact');
Route::post('/contact/send', [ContactController::class, 'send'])->name('contact.send');


Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
});

Route::middleware(['auth:sanctum', 'verified'])->group(function () {

    Route::middleware('role:seller')->group(function () {
        // Product management views
        Route::get('products', function () {
            return view('seller.products');
        })->name('products.index');

        Route::get('products/create', function () {
            return view('seller.add-or-edit-product');
        })->name('products.create');

        Route::get('products/{productId}/edit', function ($productId) {
            return view('seller.add-or-edit-product', ['productId' => $productId]);
        })->name('products.edit');

        Route::get('orders', function () {
            return view('seller.orders');
        })->name('seller.orders');
    });

    Route::middleware('role:buyer')->prefix('buyer')->group(function () {

        Route::get('products/{product}', function ($product) {
            return view('buyer.product-view', ['productId' => $product]);
        })->name('buyer.products.show');

        Route::get('cart', function () {
            return view('buyer.cart');
        })->name('buyer.cart');

        Route::get('orders', function () {
            return view('buyer.orders');
        })->name('buyer.orders');

        Route::get('/checkout/success', [CheckoutController::class, 'success'])->name('checkout.success');
        Route::get('/checkout/cancel', [CheckoutController::class, 'cancel'])->name('checkout.cancel');
    });
});