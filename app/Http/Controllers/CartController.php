<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\CartItem;
use Illuminate\Http\Request;
use App\Http\Resources\CartResource;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
        ]);

        $cart = Cart::firstOrCreate(
            ['user_id' => Auth::id()],
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
        
        return new CartResource($cart->load('cartItems.product'));
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $cart = Cart::with('cartItems.product')->where('user_id', $id)->firstOrFail();
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
     */
    public function destroy(string $id)
    {
        // $validated = $request->validate([
        //     'product_id' => 'required|exists:products,id',
        // ]);

        $cart = Cart::where('user_id', Auth::id())->firstOrFail();

        $item = CartItem::where('cart_id', $cart->id)
            ->where('product_id', $id)->first();

        if (!$item) {
            return response()->json(['message' => 'Item not found in cart.'], 404);
        }

        $item->delete();

        return new CartResource($cart->load('cartItems.product'));
    }
}
