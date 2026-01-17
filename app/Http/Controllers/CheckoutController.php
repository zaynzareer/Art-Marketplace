<?php

namespace App\Http\Controllers;

use App\Jobs\SendOrderConfirmationEmail;
use App\Models\Order;
use Stripe\Stripe;
use Illuminate\Support\Facades\DB;
use Stripe\Checkout\Session;
use Illuminate\Support\Facades\Auth;

class CheckoutController extends Controller
{
    /**
     * Create Stripe checkout session using API URLs for success/cancel.
     */
    public function stripeCheckout()
    {
        $user = Auth::user();

        $cart = $user->cart()->with('cartItems.product')->first();

        if (!$cart || $cart->cartItems->isEmpty()) {
            return response()->json([
                'message' => 'Cart is empty'
            ], 422);
        }

        Stripe::setApiKey(config('services.stripe.secret'));

        $lineItems = $cart->cartItems->map(function ($item) {
            return [
                'price_data' => [
                    'currency' => 'usd',
                    'product_data' => [
                        'name' => $item->product->name,
                    ],
                    'unit_amount' => (int) ($item->product->price * 100),
                ],
                'quantity' => $item->quantity,
            ];
        })->toArray();

        $session = Session::create([
            'mode' => 'payment',
            'line_items' => $lineItems,
            'success_url' => route('checkout.success'),
            'cancel_url' => route('checkout.cancel'),
        ]);

        return response()->json([
            'url' => $session->url
        ]);
    }

    /**
     * Stripe success: create orders/order_items from cart, then clear cart.
     */
    public function success()
    {
        $user = Auth::user();

        $cart = $user->cart()->with('cartItems.product')->first();

        if (!$cart || $cart->cartItems->isEmpty()) {
            return view('checkout.success');
        }

        try {
            DB::beginTransaction();

            $itemsBySeller = $cart->cartItems->groupBy(fn($item) => $item->product->seller_id);

            foreach ($itemsBySeller as $sellerId => $items) {
                $order = Order::create([
                    'buyer_id' => $user->id,
                    'seller_id' => $sellerId,
                ]);

                foreach ($items as $cartItem) {
                    $order->orderItems()->create([
                        'product_id' => $cartItem->product_id,
                        'quantity' => $cartItem->quantity,
                        'unit_price' => $cartItem->product->price,
                    ]);
                }

                // Dispatch the confirmation email job
                SendOrderConfirmationEmail::dispatch($order);
            }

            $cart->cartItems()->delete();

            DB::commit();
        } catch (\Throwable $e) {
            DB::rollBack();
            return redirect()->route('buyer.cart')->dangerBanner('Failed to finalize order: ' . $e->getMessage());
        }

        return view('checkout.success');
    }

    /**
     * Stripe cancel: keep cart, show cancel page.
     */
    public function cancel()
    {
        return view('checkout.cancel');
    }
}