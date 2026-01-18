<?php

namespace App\Livewire\Buyer;

use Livewire\Component;
use Livewire\Attributes\On;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;

class CartBadge extends Component
{
    public $cartItemCount = 0;

    #[On('cart-updated')]
    public function refreshCartCount()
    {
        $this->fetchCartCount();
    }

    public function fetchCartCount()
    {
        try {
            $buyerId = Auth::id();

            $response = Http::withToken(Session::get('api_token'))
                ->get(route('api.cart.show', $buyerId))->json('data');

            $this->cartItemCount = $response['total_items'] ?? 0;
        } catch (\Exception $e) {
            $this->cartItemCount = 0;
        }
    }

    public function mount()
    {
        $this->fetchCartCount();
    }

    public function render()
    {
        return view('livewire.buyer.cart-badge');
    }
}
