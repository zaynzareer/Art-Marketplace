<?php

namespace App\Livewire\Buyer;

use Livewire\Component;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;

class ProductView extends Component
{
    public $id;

    public $product;
    public $sellerProducts = [];

    public $quantity = 1;

    protected function fetchProduct()
    {
        $response = Http::withToken(Session::get('api_token'))
            ->get(route('api.products.show', ['product' => $this->id]))->json();

        $this->product        = $response['product'];
        $this->sellerProducts = $response['seller_products'] ?? [];
    }

    public function incrementQty()
    {
        $this->quantity++;
    }

    public function decrementQty()
    {
        if ($this->quantity > 1) {
            $this->quantity--;
        }
    }

    public function addToCart()
    {
        Http::withToken(Session::get('api_token'))
            ->post(route('api.cart.store'), [
                'product_id' => $this->id,
                'quantity'   => $this->quantity,
            ]);

        $this->dispatch('notify', message: 'Product added to cart successfully!', type: 'success');
        $this->dispatch('cart-updated');
    }

    public function mount()
    {
        $this->fetchProduct();
    }

    public function render()
    {
        return view('livewire.buyer.product-view');
    }
}
