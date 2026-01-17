<?php

namespace App\Traits;

use App\Services\CacheService;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;

trait CacheableComponent
{
    /**
     * Fetch and cache products from API
     */
    protected function fetchAndCacheProducts(string $category = null, int $page = 1, string $sort = 'newest'): ?array
    {
        $cacheKey = "products:list:" . ($category ?? 'all') . ":p{$page}:sort-{$sort}";

        return cache()->tags(['products'])->remember(
            $cacheKey,
            CacheService::PRODUCT_LIST_TTL,
            function () use ($category, $page, $sort) {
                return Http::withToken(Session::get('api_token'))
                    ->get(route('api.products.index'), [
                        'page' => $page,
                        'category' => $category,
                        'sort' => $sort,
                    ])->json('data');
            }
        );
    }

    /**
     * Fetch and cache single product with seller products
     */
    protected function fetchAndCacheProduct(int $productId): ?array
    {
        $cacheKey = "product:{$productId}:detail";

        return cache()->tags(['products'])->remember(
            $cacheKey,
            CacheService::PRODUCT_DETAIL_TTL,
            function () use ($productId) {
                return Http::withToken(Session::get('api_token'))
                    ->get(route('api.products.show', $productId))->json('data');
            }
        );
    }

    /**
     * Fetch and cache buyer cart
     */
    protected function fetchAndCacheCart(int $buyerId): ?array
    {
        $cacheKey = "cart:user:{$buyerId}";

        return cache()->tags(['carts', "user:{$buyerId}"])->remember(
            $cacheKey,
            CacheService::CART_TTL,
            function () use ($buyerId) {
                return Http::withToken(Session::get('api_token'))
                    ->get(route('api.cart.show', $buyerId))->json('data');
            }
        );
    }

    /**
     * Fetch and cache seller dashboard metrics
     */
    protected function fetchAndCacheSellerMetrics(int $sellerId): ?array
    {
        $cacheKey = "seller:{$sellerId}:metrics";

        return cache()->tags(['orders', "seller:{$sellerId}"])->remember(
            $cacheKey,
            CacheService::SELLER_METRICS_TTL,
            function () use ($sellerId) {
                return Http::withToken(Session::get('api_token'))
                    ->get(route('api.seller.dashboard'))->json('data');
            }
        );
    }

    /**
     * Fetch and cache seller orders
     */
    protected function fetchAndCacheSellerOrders(int $sellerId): ?array
    {
        $cacheKey = "seller:{$sellerId}:orders";

        return cache()->tags(['orders', "seller:{$sellerId}"])->remember(
            $cacheKey,
            CacheService::SELLER_ORDERS_TTL,
            function () use ($sellerId) {
                return Http::withToken(Session::get('api_token'))
                    ->get(route('api.seller.orders'))->json('data');
            }
        );
    }

    /**
     * Fetch and cache buyer orders
     */
    protected function fetchAndCacheBuyerOrders(int $buyerId): ?array
    {
        $cacheKey = "orders:buyer:{$buyerId}";

        return cache()->tags(['orders', "user:{$buyerId}"])->remember(
            $cacheKey,
            86400,
            function () use ($buyerId) {
                return Http::withToken(Session::get('api_token'))
                    ->get(route('api.orders.index'))->json('data');
            }
        );
    }

    /**
     * Invalidate cache - wrapper for clarity
     */
    protected function invalidateProductCache(): void
    {
        CacheService::invalidateProducts();
    }

    /**
     * Invalidate cart cache
     */
    protected function invalidateCartCache(int $buyerId): void
    {
        CacheService::invalidateCart($buyerId);
    }
}
