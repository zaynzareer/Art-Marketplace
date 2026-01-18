<?php

namespace App\Livewire\Buyer;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;

class CartPage extends Component
{
    public $items = [];
    public $subtotal = 0;

    public function fetchCart()
    {
        $buyerId = Auth::id();

        $response = Http::withToken(Session::get('api_token'))
            ->get(route('api.cart.show', $buyerId))->json('data');

        $this->items = $response['cart_items'] ?? [];
        $this->subtotal = $response['subtotal'] ?? 0;
    }

    public function updateQuantity($productId, $quantity)
    {
        if ($quantity < 1) return;

        $response = Http::withToken(Session::get('api_token'))
            ->post(route('api.cart.store'), [
                'product_id' => $productId,
                'quantity'   => $quantity,
            ]);

        if ($response->successful()) {
            $this->dispatch('notify', message: 'Cart updated', type: 'success');
        } else {
            $this->dispatch('notify', message: 'Failed to update cart', type: 'error');
        }

        $this->fetchCart();
        $this->dispatch('cart-updated');
    }

    public function removeItem($productId)
    {
        $response = Http::withToken(Session::get('api_token'))
            ->delete(route('api.cart.destroy', $productId));

        if ($response->successful()) {
            $this->dispatch('notify', message: 'Item removed from cart', type: 'success');
        } else {
            $this->dispatch('notify', message: 'Failed to remove item', type: 'error');
        }

        $this->fetchCart();
        $this->dispatch('cart-updated');
    }

    public function checkout()
    {
        $response = Http::withToken(Session::get('api_token'))
            ->post(route('api.checkout.process'));

        if ($response->successful()) {
            $checkoutUrl = $response->json('url');
            return redirect()->away($checkoutUrl);
        } else {
            $this->dispatch('notify', message: 'Failed to initiate checkout. Please try again.', type: 'error');
        }
    }

    public function mount()
    {
        $this->fetchCart();
    }

    public function render()
    {
        return view('livewire.buyer.cart-page');
    }
}
