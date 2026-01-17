<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\OrderResource;;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return OrderResource::collection(
            Order::with(['orderItems.product', 'seller'])
                ->where('buyer_id', Auth::id())
                ->orderBy('created_at', 'desc')
                ->get()
        );
    }

    /**
     * Display seller's orders.
     */
    public function sellerIndex()
    {
        return OrderResource::collection(
            Order::with(['orderItems.product', 'buyer', 'seller'])
            ->where('seller_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->get()
        );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $cart = Auth::user()->cart()->with('cartItems.product')->firstOrFail();

        if ($cart->cartItems->isEmpty()) {
            return response()->json(['message' => 'Cart is empty.'], 400);
        }

        // Group cart items by seller
        $itemsBySeller = $cart->cartItems->groupBy(fn($item) => $item->product->seller_id);

        $orders = [];

        // Create separate order for each seller
        foreach ($itemsBySeller as $sellerId => $items) {
            $order = Order::create([
                'buyer_id' => Auth::id(),
                'seller_id' => $sellerId,
                'status' => 'paid',
            ]);

            foreach ($items as $cartItem) {
                $order->orderItems()->create([
                    'product_id' => $cartItem->product_id,
                    'quantity'   => $cartItem->quantity,
                    'unit_price' => $cartItem->product->price,
                ]);
            }

            $orders[] = $order;
        }

        $cart->cartItems()->delete();

        return OrderResource::collection(collect($orders)->load('orderItems.product', 'buyer'));
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $orderId)
    {
        $order = Order::with('orderItems.product')->findOrFail($orderId);

        $validated = $request->validate([
            'status' => 'required|string|in:pending,processing,shipped,delivered,cancelled',
        ]);

        $order->update(['status' => $validated['status']]);

        return response()->json([
            'message' => 'Order status updated successfully.',
            'data' => new OrderResource($order->load('buyer', 'seller', 'orderItems.product'))
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
