<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\ProductResource;
use App\Http\Resources\DashboardResource;

class SellerDashboardController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $seller = Auth::user();

        // Revenue
        $revenue = OrderItem::query()
            ->whereHas('product', fn ($q) =>
                $q->where('seller_id', $seller->id)
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
                $q->where('seller_id', $seller->id)
            )
            ->distinct()
            ->count('orders.id');

        // Product count
        $productCount = Product::where('seller_id', $seller->id)->count();

        // Recent orders
        $recentOrders = Order::query()
            ->where('status', '!=', 'cancelled')
            ->whereHas('orderItems.product', fn ($q) =>
                $q->where('seller_id', $seller->id)
            )
            ->with(['orderItems.product'])
            ->latest()
            ->limit(5)
            ->get();

        return new DashboardResource([
            'revenue' => (float) $revenue,
            'total_orders' => (int) $totalOrders,
            'product_count' => (int) $productCount,
            'recent_orders' => $recentOrders,
        ]);
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
        //
    }
}
