<?php

namespace App\Http\Controllers;

use App\Jobs\SendOrderConfirmationEmail;
use App\Models\Order;
use Stripe\Stripe;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Stripe\Checkout\Session;
use Illuminate\Support\Facades\Auth;

class CheckoutController extends Controller
{
    /**
     * Create Stripe checkout session using API URLs for success/cancel.
     * 
     * Requires: checkout:process token scope
     */
    public function stripeCheckout()
    {
        try {
            // Validate Sanctum token scope
            if (!request()->user()->tokenCan('checkout:process')) {
                abort(403, 'Token does not have checkout:process scope');
            }
            
            // Get buyer's cart with items
            $user = Auth::user();

            $cart = $user->cart()->with('cartItems.product')->first();

            if (!$cart || $cart->cartItems->isEmpty()) {
                return response()->json([
                    'message' => 'Cart is empty'
                ], 422);
            }

            // Validate cart items still exist and prices haven't changed dramatically
            foreach ($cart->cartItems as $item) {
                if (!$item->product) {
                    return response()->json([
                        'message' => 'Some products in your cart are no longer available'
                    ], 422);
                }
                
                if ($item->product->price <= 0) {
                    return response()->json([
                        'message' => 'Invalid product price detected'
                    ], 422);
                }
            }

            // Get Stripe secret key
            Stripe::setApiKey(config('services.stripe.secret'));

            // Prepare line items for Stripe session
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

            // Create Stripe checkout session
            $session = Session::create([
                'mode' => 'payment',
                'line_items' => $lineItems,
                'success_url' => route('checkout.success'),
                'cancel_url' => route('checkout.cancel'),
            ]);

            return response()->json([
                'url' => $session->url
            ]);
            
        } catch (\Stripe\Exception\ApiErrorException $e) {
            Log::error('Stripe API error: ' . $e->getMessage(), [
                'user_id' => Auth::id()
            ]);
            
            return response()->json([
                'message' => 'Payment processing error. Please try again later.'
            ], 500);
        } catch (\Exception $e) {
            Log::error('Checkout initialization failed: ' . $e->getMessage(), [
                'user_id' => Auth::id()
            ]);
            
            return response()->json([
                'message' => 'Failed to initialize checkout. Please try again later.'
            ], 500);
        }
    }

    /**
     * Stripe success: create orders/order_items from cart, then clear cart.
     */
    public function success()
    {
        // Get buyer's cart with items
        $user = Auth::user();

        $cart = $user->cart()->with('cartItems.product')->first();

        if (!$cart || $cart->cartItems->isEmpty()) {
            return view('checkout.success');
        }

        // Transactionally create orders and clear cart
        try {
            DB::beginTransaction();

            // Group items by seller
            $itemsBySeller = $cart->cartItems->groupBy(fn($item) => $item->product->seller_id);

            // Create separate order per seller
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

            // Clear the cart
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
        // Simply return the cancel view
        return view('checkout.cancel');
    }
}
