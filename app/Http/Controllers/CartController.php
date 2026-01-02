<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use Illuminate\Http\Request;
use App\Http\Resources\CartResource;
use App\Models\CartItem;

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
            'user_id' => 'required|exists:users,id',
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
        ]);

        $cart = Cart::firstOrCreate(
            ['user_id' => $validated['user_id']],
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
        $cart = Cart::with('cartItems.product')->findOrFail($id);
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
        $cart = Cart::findOrFail($id);
        $cart->cartItems()->delete();
        $cart->delete();

        return response()->json(['message' => 'Cart deleted successfully.'], 200);
    }
}
