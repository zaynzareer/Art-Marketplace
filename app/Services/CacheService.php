<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Redis;

class CacheService
{
    /**
     * Cache keys constants with TTL
     */
    const PRODUCT_LIST_TTL = 3600; // 1 hour
    const PRODUCT_DETAIL_TTL = 7200; // 2 hours
    const SELLER_METRICS_TTL = 900; // 15 minutes
    const SELLER_ORDERS_TTL = 1800; // 30 minutes
    const CART_TTL = 86400; // 24 hours (invalidate on user action)

    /**
     * Cache tags for batch invalidation
     */
    const TAG_PRODUCTS = 'products';
    const TAG_ORDERS = 'orders';
    const TAG_CARTS = 'carts';

    /**
     * Get or cache products list with filtering
     */
    public static function getProductsList(string $category = null, int $page = 1, string $sort = 'newest'): ?array
    {
        $key = "products:list:" . ($category ?? 'all') . ":p{$page}:sort-{$sort}";

        return Cache::tags([self::TAG_PRODUCTS])
            ->remember($key, self::PRODUCT_LIST_TTL, function () use ($category, $page, $sort) {
                return null; // Will be called from controller
            });
    }

    /**
     * Get or cache single product with seller's other products
     */
    public static function getProductDetail(int $productId): ?array
    {
        $key = "product:{$productId}:detail";

        return Cache::tags([self::TAG_PRODUCTS])
            ->remember($key, self::PRODUCT_DETAIL_TTL, function () use ($productId) {
                return null; // Will be called from controller
            });
    }

    /**
     * Get or cache seller dashboard metrics
     */
    public static function getSellerMetrics(int $sellerId): ?array
    {
        $key = "seller:{$sellerId}:metrics";

        return Cache::tags([self::TAG_ORDERS, "seller:{$sellerId}"])
            ->remember($key, self::SELLER_METRICS_TTL, function () use ($sellerId) {
                return null; // Will be called from controller
            });
    }

    /**
     * Get or cache seller recent orders
     */
    public static function getSellerRecentOrders(int $sellerId, int $limit = 5): ?array
    {
        $key = "seller:{$sellerId}:recent-orders:limit-{$limit}";

        return Cache::tags([self::TAG_ORDERS, "seller:{$sellerId}"])
            ->remember($key, self::SELLER_ORDERS_TTL, function () use ($sellerId, $limit) {
                return null; // Will be called from controller
            });
    }

    /**
     * Get or cache buyer's cart
     */
    public static function getBuyerCart(int $buyerId): ?array
    {
        $key = "cart:user:{$buyerId}";

        return Cache::tags([self::TAG_CARTS, "user:{$buyerId}"])
            ->remember($key, self::CART_TTL, function () use ($buyerId) {
                return null; // Will be called from controller
            });
    }

    /**
     * Get or cache buyer's orders
     */
    public static function getBuyerOrders(int $buyerId): ?array
    {
        $key = "orders:buyer:{$buyerId}";

        return Cache::tags([self::TAG_ORDERS, "user:{$buyerId}"])
            ->remember($key, 86400, function () use ($buyerId) {
                return null; // Will be called from controller
            });
    }

    /**
     * Get or cache seller's orders
     */
    public static function getSellerOrders(int $sellerId): ?array
    {
        $key = "orders:seller:{$sellerId}";

        return Cache::tags([self::TAG_ORDERS, "seller:{$sellerId}"])
            ->remember($key, 3600, function () use ($sellerId) {
                return null; // Will be called from controller
            });
    }

    /**
     * Invalidate all product caches
     */
    public static function invalidateProducts(): void
    {
        Cache::tags([self::TAG_PRODUCTS])->flush();
    }

    /**
     * Invalidate specific product cache
     */
    public static function invalidateProduct(int $productId): void
    {
        Cache::tags([self::TAG_PRODUCTS])->flush();
        // Alternatively, invalidate only specific product:
        // Cache::forget("product:{$productId}:detail");
    }

    /**
     * Invalidate seller-specific caches
     */
    public static function invalidateSeller(int $sellerId): void
    {
        Cache::tags(["seller:{$sellerId}"])->flush();
    }

    /**
     * Invalidate cart for specific user
     */
    public static function invalidateCart(int $buyerId): void
    {
        Cache::tags([self::TAG_CARTS, "user:{$buyerId}"])->flush();
    }

    /**
     * Invalidate all order-related caches
     */
    public static function invalidateOrders(): void
    {
        Cache::tags([self::TAG_ORDERS])->flush();
    }

    /**
     * Invalidate specific buyer's orders
     */
    public static function invalidateBuyerOrders(int $buyerId): void
    {
        Cache::forget("orders:buyer:{$buyerId}");
    }

    /**
     * Invalidate specific seller's orders
     */
    public static function invalidateSellerOrders(int $sellerId): void
    {
        Cache::forget("orders:seller:{$sellerId}");
        self::invalidateSeller($sellerId);
    }

    /**
     * Manually put a value in cache with tags
     */
    public static function putWithTags(string $key, $value, int $ttl, array $tags): void
    {
        Cache::tags($tags)->put($key, $value, $ttl);
    }

    /**
     * Get value from cache or null
     */
    public static function get(string $key, $default = null)
    {
        return Cache::get($key, $default);
    }

    /**
     * Check if cache is available
     */
    public static function isAvailable(): bool
    {
        try {
            Cache::put('cache:test', 'test', 1);
            return Cache::get('cache:test') === 'test';
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * Clear all application caches
     */
    public static function clearAll(): void
    {
        Cache::flush();
    }
}
