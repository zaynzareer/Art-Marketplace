<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use App\Models\OrderItem;
use App\Services\CacheService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use App\Http\Resources\ProductResource;
use App\Http\Resources\DashboardResource;

class SellerDashboardController extends Controller
{
    /**
     * Display a listing of the resource.
     * 
     * Requires: dashboard:read token scope
     */
    public function index(Request $request)
    {
        // Validate Sanctum token scope
        if (!$request->user()->tokenCan('dashboard:read')) {
            abort(403, 'Token does not have dashboard:read scope');
        }

        // Get seller's ID
        $sellerId = Auth::id();

        // Generate Cache key
        $cacheKey = "seller:{$sellerId}:metrics";

        // Get from cache or database
        $dashboardData = Cache::tags([CacheService::TAG_ORDERS, "seller:{$sellerId}"])
            ->remember($cacheKey, CacheService::SELLER_METRICS_TTL, function () use ($sellerId) {
                // Revenue
                $revenue = OrderItem::query()
                    ->whereHas('product', fn ($q) =>
                        $q->where('seller_id', $sellerId)
                    )
                    ->whereHas('order', fn ($q) =>
                        $q->where('status', '!=', 'cancelled')
                    )
                    ->selectRaw('COALESCE(SUM(unit_price * quantity), 0) as total')
                    ->value('total');

                // Total orders
                $totalOrders = Order::query()
                    ->where('status', '!=', 'cancelled')
                    ->whereHas('orderItems.product', fn ($q) =>
                        $q->where('seller_id', $sellerId)
                    )
                    ->distinct()
                    ->count('orders.id');

                // Product count
                $productCount = Product::where('seller_id', $sellerId)->count();

                // Recent orders
                $recentOrders = Order::query()
                    ->where('status', '!=', 'cancelled')
                    ->whereHas('orderItems.product', fn ($q) =>
                        $q->where('seller_id', $sellerId)
                    )
                    ->with(['orderItems.product'])
                    ->latest()
                    ->limit(5)
                    ->get();

                // Revenue by day in current month
                $revenueByMonth = OrderItem::query()
                    ->whereHas('product', fn ($q) => $q->where('seller_id', $sellerId))
                    ->whereHas('order', fn ($q) => $q->where('status', '!=', 'cancelled'))
                    ->whereMonth('order_items.created_at', now()->month)
                    ->whereYear('order_items.created_at', now()->year)
                    ->selectRaw('DATE_FORMAT(order_items.created_at, "%Y-%m-%d") as day, SUM(unit_price * quantity) as revenue')
                    ->groupBy('day')
                    ->orderBy('day')
                    ->get()
                    ->pluck('revenue', 'day')
                    ->toArray();

                // Order status distribution
                $ordersByStatus = Order::query()
                    ->where('status', '!=', 'cancelled')
                    ->whereHas('orderItems.product', fn ($q) => $q->where('seller_id', $sellerId))
                    ->selectRaw('status, COUNT(DISTINCT orders.id) as count')
                    ->groupBy('status')
                    ->get()
                    ->pluck('count', 'status')
                    ->toArray();

                // Top selling products (all time, up to 5)
                $topProducts = OrderItem::query()
                    ->whereHas('product', fn ($q) => $q->where('seller_id', $sellerId))
                    ->whereHas('order', fn ($q) => $q->where('status', '!=', 'cancelled'))
                    ->join('products', 'order_items.product_id', '=', 'products.id')
                    ->selectRaw('products.name, SUM(order_items.quantity) as total_sold')
                    ->groupBy('products.id', 'products.name')
                    ->orderByDesc('total_sold')
                    ->limit(5)
                    ->get()
                    ->pluck('total_sold', 'name')
                    ->toArray();

                return [
                    'revenue' => (float) $revenue,
                    'total_orders' => (int) $totalOrders,
                    'product_count' => (int) $productCount,
                    'recent_orders' => $recentOrders,
                    'chart_data' => [
                        'revenue_by_month' => $revenueByMonth,
                        'orders_by_status' => $ordersByStatus,
                        'top_products' => $topProducts,
                    ],
                ];
            });

        return new DashboardResource($dashboardData);
    }
}
