<?php

namespace App\Livewire\Seller;

use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;

class ProductForm extends Component
{
    use WithFileUploads;

    public ?int $productId = null;
    public bool $isEdit = false;

    public string $name = '';
    public string $description = '';
    public $price;
    public string $category = '';
    public $image;
    public $existingImage;

    public array $categories = [
        'Paintings', 'Collectibles', 'Pottery', 'Sculptures', 'Glass Art', 'Leather Goods', 'Textiles', 'Jewelry'
    ];

    protected function rules()
    {
        return [
            'name' => 'required|string|max:50',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'category' => 'required|string',
            'image' => $this->isEdit ? 'nullable|image|max:2048' : 'required|image|max:2048',
        ];
    }

    public function loadProduct()
    {
        try{ 
            // http call to fetch product details for editing
            $response = Http::withToken(Session::get('api_token'))
                ->get(route('api.products.seller.show', $this->productId));

            // Extract product data from response
            $product = $response->json('product');

            // Handle case where product is not found
            if (!$product) {
                throw new \RuntimeException('Unable to load product details');
            }   

            // Populate component properties with product data
            $this->fill([
                'name' => $product['name'],
                'description' => $product['description'],
                'price' => $product['price'],
                'category' => $product['category'],
                'existingImage' => $product['image'],
            ]);
        } catch (\Exception $e) {
            $this->dispatch('notify', message: 'Failed to load product data', type: 'error');
        }
    }

    public function save()
    {
        $this->validate();

        $payload = [
            'name' => $this->name,
            'description' => $this->description,
            'price' => $this->price,
            'category' => $this->category,
        ];

        try {
            if ($this->isEdit) {
                // PUT for update
                if ($this->image) {
                    // Use POST with _method=PUT for multipart/form-data
                    $response = Http::withToken(Session::get('api_token'))
                        ->asMultipart()
                        ->attach(
                            'image', 
                            $this->image->get(),
                            $this->image->getClientOriginalName()
                        )
                        ->post(route('api.products.update', $this->productId), array_merge($payload, ['_method' => 'PUT']));
                } else {
                    $response = Http::withToken(Session::get('api_token'))
                        ->asForm()
                        ->put(route('api.products.update', $this->productId), $payload);
                } 
            } else {
                // POST for create
                $response = Http::withToken(Session::get('api_token'))
                    ->asMultipart()
                    ->attach(
                        'image', 
                        $this->image->get(),
                        $this->image->getClientOriginalName()
                    )
                    ->post(route('api.products.store'), $payload);            
            }

            if ($response->successful()) {
                $this->dispatch('notify', message: 'Product saved successfully', type: 'success');
                return redirect()->route('products.index');
            } else {
                $this->dispatch('notify', message: 'Failed to save product', type: 'error');
                return;
            }
        } catch (\Exception $e) {
            $this->dispatch('notify', message: 'Failed to save product: ' . $e->getMessage(), type: 'error');
            return;
        }
    }

    public function mount(?int $productId = null)
    {
        if ($productId) {
            $this->isEdit = true;
            $this->productId = $productId;
            $this->loadProduct();
        }
    }

    public function render()
    {
        return view('livewire.seller.product-form');
    }
}
