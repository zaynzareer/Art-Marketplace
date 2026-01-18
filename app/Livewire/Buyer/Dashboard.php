<?php

namespace App\Livewire\Buyer;

use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;

class Dashboard extends Component
{
    use WithPagination;

    public array $categories = [
        'all' => 'All Categories',
        'pottery' => 'Pottery',
        'paintings' => 'Paintings',
        'jewelry' => 'Jewelry',
        'sculptures' => 'Sculptures',
        'textiles' => 'Textiles',
        'glassarts' => 'Glass Arts',
        'collectibles' => 'Collectibles',
        'leathercrafts' => 'Leather Crafts',
    ];


    public $search = '';
    public $category = 'all';
    public $sort = 'newest';
    public $page = 1;

    public $products = [];
    public $meta = [];

    // Reset page and fetch products when filters are updated
    public function updated($property)
    {
        $this->resetPage();
        $this->fetchProducts();
    }

    public function fetchProducts()
    {
        $response = Http::withToken(Session::get('api_token'))->get(route('api.products.index'), [
            'search'   => $this->search,
            'category' => $this->category,
            'sort'     => $this->sort,
            'page'     => $this->page,
        ])->json();
        
        $this->products = $response['data'] ?? [];
        $this->meta     = $response['meta'] ?? [];
    }

    public function addToCart($productId)
    {
        $response = Http::withToken(Session::get('api_token'))
            ->post(route('api.cart.store'), [
                'product_id' => $productId,
                'quantity'   => 1,
            ]);

        if ($response->successful()) {
            $this->dispatch('notify', message: 'Product added to cart', type: 'success');
        } else {
            $this->dispatch('notify', message: 'Failed to add product to cart', type: 'error');
        }

        $this->dispatch('cart-updated');
    }

    public function resetPage()
    {
        $this->page = 1;
    }

    public function gotoPage($page)
    {
        $this->page = $page;
        $this->fetchProducts();
    }

    public function mount()
    {
        $this->fetchProducts();
    }

    public function render()
    {
        return view('livewire.buyer.dashboard');
    }
}
