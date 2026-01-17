<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Services\CacheService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use App\Http\Resources\ProductResource;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     * 
     * @param Request $request
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index(Request $request)
    {
        $search = $request->input('search', '');
        $category = $request->input('category', 'all');
        $sort = $request->input('sort', 'newest');
        $page = $request->input('page', 1);

        // Skip cache for search queries due to high variability
        if (!empty($search)) {
            return $this->fetchProductsFromDatabase($search, $category, $sort, $page);
        }

        // Build cache key
        $cacheKey = "products:list:" . ($category === 'all' ? 'all' : $category) . ":p{$page}:sort-{$sort}";

        // Get from cache or database
        $products = Cache::tags([CacheService::TAG_PRODUCTS])
            ->remember($cacheKey, CacheService::PRODUCT_LIST_TTL, function () use ($search, $category, $sort, $page) {
                return $this->fetchProductsFromDatabase($search, $category, $sort, $page);
            });

        return $products;
    }

    /**
     * Fetch products from database with filters
     */
    private function fetchProductsFromDatabase($search = '', $category = 'all', $sort = 'newest', $page = 1)
    {
        $query = Product::query()->with('seller');

        // Search filter
        if (!empty($search)) {
            $query->where('name', 'like', '%' . $search . '%')
                  ->orWhere('description', 'like', '%' . $search . '%');
        }

        // Category filter
        if ($category !== 'all') {
            $query->where('category', $category);
        }

        // Sorting
        match ($sort) {
            'price_asc'  => $query->orderBy('price', 'asc'),
            'price_desc' => $query->orderBy('price', 'desc'),
            'newest'     => $query->orderBy('created_at', 'desc'),
            default      => $query->orderBy('created_at', 'desc'),
        };

        $products = $query->paginate(10);

        return ProductResource::collection($products);
    }

    /**
     * Display a listing of the resource for the authenticated seller.
     */
    public function sellerIndex(Request $request)
    {
        $search = $request->input('search', '');
        $category = $request->input('category', 'all');

        $query = Product::where('seller_id', Auth::id());

        if (!empty($search)) {
            $query->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
        }

        if ($category !== 'all') {
            $query->where('category', $category);
        }

        $products = $query->latest()->paginate(8);

        return ProductResource::collection($products);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'        => 'required|string|max:50',
            'description' => 'nullable|string',
            'price'       => 'required|numeric|min:0',
            'category'    => 'required|string',
            'image'       => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);
        
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('products', 'public');
            $validated['image'] = $imagePath;
        }

        $validated['seller_id'] = Auth::id();

        $product = Product::create($validated);
        return new ProductResource($product);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        // Build cache key
        $cacheKey = "product:{$id}:detail";

        // Get from cache or database
        $response = Cache::tags([CacheService::TAG_PRODUCTS])
            ->remember($cacheKey, CacheService::PRODUCT_DETAIL_TTL, function () use ($id) {
                $product = Product::with('seller')->findOrFail($id);
                
                $sellerProducts = Product::where('seller_id', $product->seller_id)
                    ->where('id', '!=', $id)
                    ->limit(8)
                    ->get();

                return [
                    'product' => new ProductResource($product),
                    'seller_products' => ProductResource::collection($sellerProducts),
                ];
            });

        return response()->json($response);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $product = Product::findOrFail($id);

        $validated = $request->validate([
            'name'        => 'sometimes|required|string|max:50',
            'description' => 'sometimes|nullable|string',
            'price'       => 'sometimes|required|numeric|min:0',
            'category'    => 'sometimes|required|string',
            'image'       => 'sometimes|required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('products', 'public');
            $validated['image'] = $imagePath;
        }

        $product->update($validated);
        return new ProductResource($product);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $product = Product::findOrFail($id);

        Storage::disk('public')->delete($product->image);
        
        $product->delete();

        return response()->json(['message' => 'Product deleted successfully.'], 200);

    }
}
