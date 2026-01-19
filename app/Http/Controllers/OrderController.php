<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Validation\ValidationException;
use Illuminate\Auth\Access\AuthorizationException;
use App\Models\Order;
use App\Services\CacheService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
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
        // Authorization check using Policy
        $this->authorize('viewAny', Order::class);
        
        // Validate Sanctum token scope
        if (!$request->user()->tokenCan('orders:read')) {
            abort(403, 'Token does not have orders:read scope');
        }

        // Get buyer's ID
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

        // Get seller's ID
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
     * Update the specified resource in storage.
     * 
     * Requires: orders:update-status token scope
     */
    public function update(Request $request, string $orderId)
    {
        try {
            // Validate ID format
            if (!is_numeric($orderId) || $orderId <= 0) {
                return response()->json(['message' => 'Invalid order ID'], 400);
            }
            
            // Retrieve order
            $order = Order::with('orderItems.product')->findOrFail($orderId);

            // Authorization check using Policy
            $this->authorize('update', $order);
            
            // Validate Sanctum token scope
            if (!$request->user()->tokenCan('orders:update-status')) {
                abort(403, 'Token does not have orders:update-status scope');
            }

            $validated = $request->validate([
                'status' => 'required|string|in:pending,processing,shipped,delivered,cancelled',
            ]);

            // Prevent status changes to delivered orders
            if ($order->status === 'delivered' && $validated['status'] !== 'delivered') {
                return response()->json([
                    'message' => 'Cannot change status of delivered orders'
                ], 422);
            }

            // Update status
            $order->update(['status' => $validated['status']]);

            return response()->json([
                'message' => 'Order status updated successfully',
                'data' => new OrderResource($order->load('buyer', 'seller', 'orderItems.product'))
            ], 200);
            
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Order not found'], 404);
        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        } catch (AuthorizationException $e) {
            return response()->json(['message' => 'Unauthorized to update this order'], 403);
        } catch (\Exception $e) {
            Log::error('Order update failed: ' . $e->getMessage(), [
                'order_id' => $orderId,
                'user_id' => Auth::id()
            ]);
            
            return response()->json([
                'message' => 'Failed to update order. Please try again later.'
            ], 500);
        }
    }
}
