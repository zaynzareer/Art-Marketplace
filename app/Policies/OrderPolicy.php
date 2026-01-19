<?php

namespace App\Policies;

use App\Models\Order;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class OrderPolicy
{
    use HandlesAuthorization;

    /**
     * Buyers and sellers can list only their own orders (enforced in controller queries).
     */
    public function viewAny(User $user): bool
    {
        return in_array($user->role, ['buyer', 'seller'], true);
    }

    /**
     * A buyer or the seller involved can view an order.
     */
    public function view(User $user, Order $order): bool
    {
        return $user->id === $order->buyer_id || $user->id === $order->seller_id;
    }

    /**
     * Only buyers can create orders (checkout).
     */
    public function create(User $user): bool
    {
        return $user->role === 'buyer';
    }

    /**
     * Only the seller attached to the order can update its status.
     */
    public function update(User $user, Order $order): bool
    {
        return $user->role === 'seller' && $user->id === $order->seller_id;
    }

    /**
     * Prevent deleting orders by default.
     */
    public function delete(User $user, Order $order): bool
    {
        return false;
    }
}
