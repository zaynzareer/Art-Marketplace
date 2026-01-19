<?php

namespace App\Providers;

use App\Models\Cart;
use App\Models\Order;
use App\Models\Product;
use App\Policies\CartPolicy;
use App\Policies\OrderPolicy;
use App\Policies\ProductPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        Product::class => ProductPolicy::class,
        Order::class   => OrderPolicy::class,
        Cart::class    => CartPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        $this->registerPolicies();
        $this->registerGates();
    }

    /**
     * Register authorization gates for role-based access control.
     *
     * Gates are used for simple authorization checks in Blade templates using @can directive.
     */
    private function registerGates(): void
    {
        // Seller gates
        Gate::define('view-seller-nav', function ($user) {
            return $user && $user->role === 'seller';
        });

        Gate::define('manage-products', function ($user) {
            return $user && $user->role === 'seller';
        });

        Gate::define('view-seller-dashboard', function ($user) {
            return $user && $user->role === 'seller';
        });

        Gate::define('view-seller-orders', function ($user) {
            return $user && $user->role === 'seller';
        });

        // Buyer gates
        Gate::define('view-buyer-nav', function ($user) {
            return $user && $user->role === 'buyer';
        });

        Gate::define('view-cart', function ($user) {
            return $user && $user->role === 'buyer';
        });

        Gate::define('view-buyer-orders', function ($user) {
            return $user && $user->role === 'buyer';
        });

        // Shared gates
        Gate::define('view-products-catalog', function ($user) {
            return $user !== null;
        });

        Gate::define('view-about', function ($user) {
            return $user !== null;
        });

        Gate::define('view-contact', function ($user) {
            return $user !== null;
        });
    }
}
