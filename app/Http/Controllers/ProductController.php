<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
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
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $path = $request->file('image')->store('products', 'public');

        $validated = $request->validate([
            'name'        => 'required|string|max:50',
            'description' => 'nullable|string',
            'price'       => 'required|numeric|min:0',
            'image'       => 'required|string',
            'seller_id'   => 'required|exists:users,id',
        ]);
        $validated['image'] = $path;

        $product = Product::create($validated);
        return new ProductResource($product);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        return new ProductResource(Product::findOrFail($id));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $product = Product::findOrFail($id);

        $path = $request->file('image')->store('products', 'public');

        $validated = $request->validate([
            'name'        => 'sometimes|required|string|max:50',
            'description' => 'sometimes|nullable|string',
            'price'       => 'sometimes|required|numeric|min:0',
            'image'       => 'sometimes|required|string',
        ]);
        $validated['image'] = $path;

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
