<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Validation\ValidationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Auth\Access\AuthorizationException;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use App\Services\CacheService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use App\Http\Resources\CartResource;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    use AuthorizesRequests;

    /**
     * Store a newly created resource in storage.
     * 
     * Requires: cart:write token scope
     */
    public function store(Request $request)
    {
        try {
            // Authorization check using Policy
            $this->authorize('create', Cart::class);
            
            // Validate Sanctum token scope
            if (!$request->user()->tokenCan('cart:write')) {
                abort(403, 'Token does not have cart:write scope');
            }

            $validated = $request->validate([
                'product_id' => 'required|integer|exists:products,id',
                'quantity' => 'required|integer|min:1|max:100',
            ]);

            $buyerId = Auth::id();

            // Verify product exists and is available
            $product = Product::findOrFail($validated['product_id']);
            
            // Prevent adding own products to cart (sellers can't buy from themselves)
            if ($product->seller_id === $buyerId) {
                return response()->json([
                    'message' => 'Cannot add your own product to cart'
                ], 422);
            }

            // Create or get existing cart
            $cart = Cart::firstOrCreate(
                ['user_id' => $buyerId],
            );

            // Update existing item or create new
            CartItem::updateOrCreate(
                [
                    'cart_id'    => $cart->id,
                    'product_id' => $validated['product_id']
                ],
                [
                    'quantity'   => $validated['quantity']
                ]
            );

            // Invalidate cart cache
            CacheService::invalidateCart($buyerId);
            
            return response()->json([
                'message' => 'Product added to cart successfully',
                'data' => new CartResource($cart->load('cartItems.product'))
            ], 200);
            
        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Product not found'], 404);
        } catch (AuthorizationException $e) {
            return response()->json(['message' => 'Unauthorized to modify cart'], 403);
        } catch (\Exception $e) {
            Log::error('Cart update failed: ' . $e->getMessage(), [
                'user_id' => Auth::id(),
                'product_id' => $request->input('product_id')
            ]);
            
            return response()->json([
                'message' => 'Failed to add item to cart. Please try again later.'
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     * 
     * Requires: cart:read token scope
     */
    public function show(Request $request, string $id)
    {
        try {
            // Validate ID format
            if (!is_numeric($id) || $id <= 0) {
                return response()->json(['message' => 'Invalid cart ID'], 400);
            }
            
            // Validate Sanctum token scope
            if (!$request->user()->tokenCan('cart:read')) {
                abort(403, 'Token does not have cart:read scope');
            }

            $buyerId = Auth::id();

            // Prevent accessing another user's cart via URL manipulation
            if ((int) $id !== $buyerId) {
                abort(403, 'Unauthorized access to cart.');
            }

            // Build cache key
            $cacheKey = "cart:user:{$buyerId}";

            // Get from cache or database
            $cart = Cache::tags([CacheService::TAG_CARTS, "user:{$buyerId}"])
                ->remember($cacheKey, CacheService::CART_TTL, function () use ($buyerId) {
                    return Cart::with('cartItems.product')
                        ->where('user_id', $buyerId)
                        ->firstOrFail();
                });
            
            // Authorization check using Policy
            $this->authorize('view', $cart);

            return new CartResource($cart);
            
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Cart not found'], 404);
        } catch (AuthorizationException $e) {
            return response()->json(['message' => 'Unauthorized to view this cart'], 403);
        } catch (\Exception $e) {
            Log::error('Cart retrieval failed: ' . $e->getMessage(), [
                'user_id' => Auth::id(),
                'cart_id' => $id
            ]);
            
            return response()->json([
                'message' => 'Failed to retrieve cart. Please try again later.'
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     * 
     * Requires: cart:write token scope
     */
    public function destroy(Request $request, string $id)
    {
        try {
            // Validate ID format (product_id)
            if (!is_numeric($id) || $id <= 0) {
                return response()->json(['message' => 'Invalid product ID'], 400);
            }
            
            // Validate Sanctum token scope
            if (!$request->user()->tokenCan('cart:write')) {
                abort(403, 'Token does not have cart:write scope');
            }

            // Get buyer's cart
            $buyerId = Auth::id();
            $cart = Cart::where('user_id', $buyerId)->firstOrFail();

            // Authorization check using Policy
            $this->authorize('delete', $cart);

            // Find and delete the cart item
            $item = CartItem::where('cart_id', $cart->id)
                ->where('product_id', $id)->first();

            if (!$item) {
                return response()->json(['message' => 'Item not found in cart'], 404);
            }

            $item->delete();

            // Invalidate cart cache
            CacheService::invalidateCart($buyerId);

            return response()->json([
                'message' => 'Item removed from cart successfully',
                'data' => new CartResource($cart->load('cartItems.product'))
            ], 200);
            
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Cart not found'], 404);
        } catch (AuthorizationException $e) {
            return response()->json(['message' => 'Unauthorized to modify this cart'], 403);
        } catch (\Exception $e) {
            Log::error('Cart item removal failed: ' . $e->getMessage(), [
                'user_id' => Auth::id(),
                'product_id' => $id
            ]);
            
            return response()->json([
                'message' => 'Failed to remove item from cart. Please try again later.'
            ], 500);
        }
    }
}
