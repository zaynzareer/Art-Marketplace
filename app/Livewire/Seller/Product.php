<?php

namespace App\Livewire\Seller;

use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;

class Product extends Component
{
    use WithPagination;

    public $search = '';
    public $category = 'all';
    public $page = 1;

    public $products = [];
    public $meta = [];

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

    // Reset page and fetch products when filters are updated
    public function updated($property)
    {
        $this->resetPage();
        $this->fetchProducts();
    }

    public function deleteProduct(int $productId)
    {
        $response = Http::withToken(Session::get('api_token'))->delete(route('api.products.destroy', $productId));
        
        if ($response->successful()) {
            $this->dispatch('notify', message: 'Product deleted successfully', type: 'success');
            $this->updated('products');
        } else {
            $this->dispatch('notify', message: 'Failed to delete product', type: 'error');
        }
    }
    
    public function fetchProducts()
    {
        $response = Http::withToken(Session::get('api_token'))->get(route('api.products.sellerIndex'), [
            'search'   => $this->search,
            'category' => $this->category,
            'page'     => $this->page,
        ])->json();

        $this->products = $response['data'];
        $this->meta     = $response['meta'];
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
        return view('livewire.seller.product');
    }
}
