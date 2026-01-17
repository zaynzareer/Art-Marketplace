<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\ProductResource;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // eager load seller relationship
        $query = Product::query()->with('seller');

        // Search filter
        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        // Category filter
        if ($request->filled('category') && $request->category !== 'all') {
            $query->where('category', $request->category);
        }

        // Sorting
        if ($request->filled('sort')) {
            match ($request->sort) {
                'price_asc'  => $query->orderBy('price', 'asc'),
                'price_desc' => $query->orderBy('price', 'desc'),
                'newest'     => $query->orderBy('created_at', 'desc'),
                default      => null,
            };
        } else {
            // Default sort
            $query->orderBy('created_at', 'desc');
        }

        $products = $query->paginate(10);

        return ProductResource::collection($products);
    }

    /**
     * Display a listing of the resource for the authenticated seller.
    */
    public function sellerIndex(Request $request)
    {
        $products = Product::where('seller_id', Auth::id())
            ->when($request->search, fn ($q) =>
                $q->where('name', 'like', "%{$request->search}%")
            )
            ->when(
                $request->category && $request->category !== 'all',
                fn ($q) => $q->where('category', $request->category)
            )
            ->latest()
            ->paginate(8);

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
        $product = Product::findOrFail($id);
        
        $sellerProducts = Product::where('seller_id', $product->seller_id)
            ->where('id', '!=', $id)
            ->limit(8)
            ->get();

        return response()->json([
            'product' => new ProductResource($product),
            'seller_products' => ProductResource::collection($sellerProducts),
        ]);
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
