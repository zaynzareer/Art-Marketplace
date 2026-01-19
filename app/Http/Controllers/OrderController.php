<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use App\Models\Order;
use App\Services\CacheService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use App\Http\Resources\OrderResource;

class OrderController extends Controller
{
    use AuthorizesRequests;
    /**
     * Display a listing of the resource.
     * 
     * Requires: orders:read token scope
     */
    public function index(Request $request)
    {
        $this->authorize('viewAny', Order::class);
        
        // Validate Sanctum token scope
        if (!$request->user()->tokenCan('orders:read')) {
            abort(403, 'Token does not have orders:read scope');
        }

        $buyerId = Auth::id();
        $cacheKey = "orders:buyer:{$buyerId}";

        // Get from cache or database
        $orders = Cache::tags([CacheService::TAG_ORDERS, "user:{$buyerId}"])
            ->remember($cacheKey, 86400, function () use ($buyerId) {
                return Order::with(['orderItems.product', 'seller'])
                    ->where('buyer_id', $buyerId)
                    ->orderBy('created_at', 'desc')
                    ->get();
            });

        return OrderResource::collection($orders);
    }

    /**
     * Display seller's orders.
     * 
     * Requires: orders:read token scope
     */
    public function sellerIndex(Request $request)
    {
        $this->authorize('viewAny', Order::class);
        
        // Validate Sanctum token scope
        if (!$request->user()->tokenCan('orders:read')) {
            abort(403, 'Token does not have orders:read scope');
        }

        $sellerId = Auth::id();
        $cacheKey = "orders:seller:{$sellerId}";

        // Get from cache or database
        $orders = Cache::tags([CacheService::TAG_ORDERS, "seller:{$sellerId}"])
            ->remember($cacheKey, 3600, function () use ($sellerId) {
                return Order::with(['orderItems.product', 'buyer', 'seller'])
                    ->where('seller_id', $sellerId)
                    ->orderBy('created_at', 'desc')
                    ->get();
            });

        return OrderResource::collection($orders);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
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
     * 
     * Requires: orders:update-status token scope
     */
    public function update(Request $request, string $orderId)
    {
        $order = Order::with('orderItems.product')->findOrFail($orderId);

        $this->authorize('update', $order);
        
        // Validate Sanctum token scope
        if (!$request->user()->tokenCan('orders:update-status')) {
            abort(403, 'Token does not have orders:update-status scope');
        }

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
