<?php

namespace App\Policies;

use App\Models\Product;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ProductPolicy
{
    use HandlesAuthorization;

    /**
     * Anyone can view the product catalog.
     */
    public function viewAny(?User $user): bool
    {
        return true;
    }

    /**
     * Anyone can view a product detail.
     */
    public function view(?User $user, Product $product): bool
    {
        return true;
    }

    /**
     * Only sellers can create products.
     */
    public function create(User $user): bool
    {
        return $user->role === 'seller';
    }

    /**
     * Sellers can update their own products.
     */
    public function update(User $user, Product $product): bool
    {
        return $user->role === 'seller' && $user->id === $product->seller_id;
    }

    /**
     * Sellers can delete their own products.
     */
    public function delete(User $user, Product $product): bool
    {
        return $user->role === 'seller' && $user->id === $product->seller_id;
    }
}
