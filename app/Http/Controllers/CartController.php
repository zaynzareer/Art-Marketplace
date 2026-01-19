<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use App\Models\Cart;
use App\Models\CartItem;
use App\Services\CacheService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use App\Http\Resources\CartResource;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    use AuthorizesRequests;
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     * 
     * Requires: cart:write token scope
     */
    public function store(Request $request)
    {
        $this->authorize('create', Cart::class);
        
        // Validate Sanctum token scope
        if (!$request->user()->tokenCan('cart:write')) {
            abort(403, 'Token does not have cart:write scope');
        }

        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
        ]);

        $buyerId = Auth::id();

        $cart = Cart::firstOrCreate(
            ['user_id' => $buyerId],
        );

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
        
        return new CartResource($cart->load('cartItems.product'));
    }

    /**
     * Display the specified resource.
     * 
     * Requires: cart:read token scope
     */
    public function show(Request $request, string $id)
    {
        // Validate Sanctum token scope
        if (!$request->user()->tokenCan('cart:read')) {
            abort(403, 'Token does not have cart:read scope');
        }

        $buyerId = Auth::id();

        // Prevent accessing another user's cart via URL manipulation
        if ((int) $id !== $buyerId) {
            abort(403, 'Unauthorized access to cart.');
        }

        $cacheKey = "cart:user:{$buyerId}";

        // Get from cache or database
        $cart = Cache::tags([CacheService::TAG_CARTS, "user:{$buyerId}"])
            ->remember($cacheKey, CacheService::CART_TTL, function () use ($buyerId) {
                return Cart::with('cartItems.product')
                    ->where('user_id', $buyerId)
                    ->firstOrFail();
            });

        $this->authorize('view', $cart);

        return new CartResource($cart);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     * 
     * Requires: cart:write token scope
     */
    public function destroy(Request $request, string $id)
    {
        // Validate Sanctum token scope
        if (!$request->user()->tokenCan('cart:write')) {
            abort(403, 'Token does not have cart:write scope');
        }

        $buyerId = Auth::id();
        $cart = Cart::where('user_id', $buyerId)->firstOrFail();

        $this->authorize('delete', $cart);

        $item = CartItem::where('cart_id', $cart->id)
            ->where('product_id', $id)->first();

        if (!$item) {
            return response()->json(['message' => 'Item not found in cart.'], 404);
        }

        $item->delete();

        // Invalidate cart cache
        CacheService::invalidateCart($buyerId);

        return new CartResource($cart->load('cartItems.product'));
    }
}
