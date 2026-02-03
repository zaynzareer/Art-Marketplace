<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Validation\ValidationException;
use App\Models\Product;
use App\Services\CacheService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use App\Http\Resources\ProductResource;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    use AuthorizesRequests;
    /**
     * Display a listing of the resource.
     * 
     * @param Request $request
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index(Request $request)
    {
        // Get filters
        $search = $request->input('search', '');
        $category = $request->input('category', 'all');
        $sort = $request->input('sort', 'newest');
        $page = $request->input('page', 1);

        // Skip cache for search queries due to high variability
        if (!empty($search)) {
            return $this->fetchProductsFromDatabase($search, $category, $sort);
        }

        // Build cache key
        $cacheKey = "products:list:" . ($category === 'all' ? 'all' : $category) . ":sort-{$sort}:page-{$page}";

        // Get from cache or database
        $products = Cache::tags([CacheService::TAG_PRODUCTS])
            ->remember($cacheKey, CacheService::PRODUCT_LIST_TTL, function () use ($search, $category, $sort, $page) {
                // paginate() reads current page from request; include $page in cache key so each page caches separately
                return $this->fetchProductsFromDatabase($search, $category, $sort);
            });

        return $products;
    }

    /**
     * Fetch products from database with filters
     */
    private function fetchProductsFromDatabase($search = '', $category = 'all', $sort = 'newest')
    {   
        // Build query
        $query = Product::query()->with('seller');

        // Search filter - use grouped where clause to ensure proper AND/OR logic
        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                  ->orWhere('description', 'like', '%' . $search . '%');
            });
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

        // Pagination
        $products = $query->paginate(10);

        return ProductResource::collection($products);
    }

    /**
     * Display a listing of the resource for the authenticated seller.
     */
    public function sellerIndex(Request $request)
    {   
        // Authorization check using Policy
        $this->authorize('viewAny', Product::class);

        // Get filters
        $search = $request->input('search', '');
        $category = $request->input('category', 'all');

        // Build query
        $query = Product::where('seller_id', Auth::id());

        // Search filter - use whereAny to search across multiple columns
        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // Category filter
        if ($category !== 'all') {
            $query->where('category', $category);
        }

        // Pagination
        $products = $query->latest()->paginate(8);

        return ProductResource::collection($products);
    }

    /**
     * Display a product for the authenticated seller (edit context).
     * Requires: products:update token scope and ownership via policy.
     */
    public function sellerShow(Request $request, string $id)
    {
        // Basic ID validation
        if (!is_numeric($id) || $id <= 0) {
            return response()->json(['message' => 'Invalid product ID'], 400);
        }

        // Ensure token has appropriate scope for editing
        if (!$request->user()->tokenCan('products:update')) {
            return response()->json(['message' => 'Token does not have products:update scope'], 403);
        }

        // Retrieve and authorize ownership
        $product = Product::with('seller')->findOrFail($id);

        // Authorization check using Policy
        $this->authorize('update', $product);

        return response()->json([
            'product' => new ProductResource($product),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     * 
     * Requires: products:create token scope
     */
    public function store(Request $request)
    {
        try {
            // Authorization check using Policy
            $this->authorize('create', Product::class);
            
            // Validate Sanctum token scope
            if (!$request->user()->tokenCan('products:create')) {
                abort(403, 'Token does not have products:create scope');
            }

            $validated = $request->validate([
                'name'        => 'required|string|max:100|min:3',
                'description' => 'nullable|string|max:1000',
                'price'       => 'required|numeric|min:0.01|max:999999.99',
                'category'    => 'required|string|in:pottery,paintings,jewelry,sculptures,textiles,glassarts,collectibles,leathercrafts',
                'image'       => 'required|image|mimes:jpeg,png,jpg,webp|max:5120',
            ]);
            
            // Handle image upload
            if ($request->hasFile('image')) {
                $image = $request->file('image');
                
                // Additional security: Check actual image dimensions
                $imageSize = getimagesize($image->getRealPath());
                if (!$imageSize || $imageSize[0] > 4000 || $imageSize[1] > 4000) {
                    return response()->json([
                        'message' => 'Image dimensions must not exceed 4000x4000 pixels'
                    ], 422);
                }
                
                // Store image
                $imagePath = $image->store('products');
                $validated['image'] = $imagePath;
            }

            // Assign authenticated user as seller
            $validated['seller_id'] = Auth::id();

            $product = Product::create($validated);
            
            return response()->json([
                'message' => 'Product created successfully',
                'data' => new ProductResource($product)
            ], 201);
            
        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        } catch (AuthorizationException $e) {
            return response()->json([
                'message' => 'Unauthorized to create products'
            ], 403);
        } catch (\Exception $e) {
            Log::error('Product creation failed: ' . $e->getMessage(), [
                'user_id' => Auth::id(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'message' => 'Failed to create product. Please try again later.'
            ], 500);
        }
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
            
                // Fetch other products from the same seller
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
     * 
     * Requires: products:update token scope
     */
    public function update(Request $request, string $id)
    {
        try {
            // Validate ID format
            if (!is_numeric($id) || $id <= 0) {
                return response()->json(['message' => 'Invalid product ID'], 400);
            }

            // Retrieve product
            $product = Product::findOrFail($id);

            // Authorization check using Policy
            $this->authorize('update', $product);
            
            // Validate Sanctum token scope
            if (!$request->user()->tokenCan('products:update')) {
                abort(403, 'Token does not have products:update scope');
            }

            $validated = $request->validate([
                'name'        => 'sometimes|required|string|max:100|min:3',
                'description' => 'sometimes|nullable|string|max:1000',
                'price'       => 'sometimes|required|numeric|min:0.01|max:999999.99',
                'category'    => 'sometimes|required|string|in:pottery,paintings,jewelry,sculptures,textiles,glassarts,collectibles,leathercrafts',
                'image'       => 'sometimes|image|mimes:jpeg,png,jpg,webp|max:5120',
            ]);

            // Handle image upload
            if ($request->hasFile('image')) {
                $image = $request->file('image');
                
                // Validate image dimensions
                $imageSize = getimagesize($image->getRealPath());
                if (!$imageSize || $imageSize[0] > 4000 || $imageSize[1] > 4000) {
                    return response()->json([
                        'message' => 'Image dimensions must not exceed 4000x4000 pixels'
                    ], 422);
                }
                
                // Delete old image
                if ($product->image) {
                    Storage::delete($product->image);
                }
                
                // Store new image
                $imagePath = $image->store('products');
                $validated['image'] = $imagePath;
            }

            $product->update($validated);
            
            return response()->json([
                'message' => 'Product updated successfully',
                'data' => new ProductResource($product)
            ], 200);
            
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Product not found'], 404);
        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        } catch (AuthorizationException $e) {
            return response()->json(['message' => 'Unauthorized to update this product'], 403);
        } catch (\Exception $e) {
            Log::error('Product update failed: ' . $e->getMessage(), [
                'product_id' => $id,
                'user_id' => Auth::id()
            ]);
            
            return response()->json([
                'message' => 'Failed to update product. Please try again later.'
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     * 
     * Requires: products:delete token scope
     */
    public function destroy(Request $request, string $id)
    {
        try {
            // Validate ID format
            if (!is_numeric($id) || $id <= 0) {
                return response()->json(['message' => 'Invalid product ID'], 400);
            }
            
            // Retrieve product
            $product = Product::findOrFail($id);

            // Authorization check using Policy
            $this->authorize('delete', $product);
            
            // Validate Sanctum token scope
            if (!$request->user()->tokenCan('products:delete')) {
                abort(403, 'Token does not have products:delete scope');
            }

            // Check if product has associated orders (prevent deletion if sold)
            if ($product->orderItems()->exists()) {
                return response()->json([
                    'message' => 'Cannot delete product with existing orders. Consider marking it as unavailable instead.'
                ], 409);
            }

            // Delete image file
            if ($product->image) {
                Storage::delete($product->image);
            }
            
            // Delete product
            $product->delete();

            return response()->json(['message' => 'Product deleted successfully'], 200);
            
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Product not found'], 404);
        } catch (AuthorizationException $e) {
            return response()->json(['message' => 'Unauthorized to delete this product'], 403);
        } catch (\Exception $e) {
            Log::error('Product deletion failed: ' . $e->getMessage(), [
                'product_id' => $id,
                'user_id' => Auth::id()
            ]);
            
            return response()->json([
                'message' => 'Failed to delete product. Please try again later.'
            ], 500);
        }
    }
}
