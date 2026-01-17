<?php

namespace App\Helpers;

use App\Services\CacheService;
use Illuminate\Support\Facades\Cache;

class CacheHelper
{
    /**
     * Get cache status information as array
     */
    public static function getStatus(): array
    {
        return [
            'driver' => config('cache.default'),
            'is_available' => CacheService::isAvailable(),
            'config' => [
                'products_ttl' => CacheService::PRODUCT_LIST_TTL . 's',
                'detail_ttl' => CacheService::PRODUCT_DETAIL_TTL . 's',
                'metrics_ttl' => CacheService::SELLER_METRICS_TTL . 's',
            ]
        ];
    }

    /**
     * Format cache TTL to human-readable format
     */
    public static function formatTTL(int $seconds): string
    {
        if ($seconds < 60) {
            return "{$seconds} seconds";
        } elseif ($seconds < 3600) {
            $minutes = round($seconds / 60);
            return "{$minutes} minute" . ($minutes > 1 ? 's' : '');
        } elseif ($seconds < 86400) {
            $hours = round($seconds / 3600);
            return "{$hours} hour" . ($hours > 1 ? 's' : '');
        } else {
            $days = round($seconds / 86400);
            return "{$days} day" . ($days > 1 ? 's' : '');
        }
    }

    /**
     * Get cache statistics (if available)
     */
    public static function getStats(): ?array
    {
        try {
            if (config('cache.default') === 'redis') {
                $redis = Cache::getRedis();
                $info = $redis->info('stats');
                
                return [
                    'hits' => $info['keyspace_hits'] ?? 0,
                    'misses' => $info['keyspace_misses'] ?? 0,
                    'hit_rate' => self::calculateHitRate($info),
                ];
            }
            
            return null;
        } catch (\Exception $e) {
            return null;
        }
    }

    /**
     * Calculate cache hit rate percentage
     */
    private static function calculateHitRate(array $info): string
    {
        $hits = $info['keyspace_hits'] ?? 0;
        $misses = $info['keyspace_misses'] ?? 0;
        $total = $hits + $misses;

        if ($total === 0) {
            return '0%';
        }

        $percentage = round(($hits / $total) * 100, 2);
        return "{$percentage}%";
    }

    /**
     * Clear cache and return success message
     */
    public static function clear(string $type = 'all'): string
    {
        try {
            match ($type) {
                'all' => CacheService::clearAll(),
                'products' => CacheService::invalidateProducts(),
                'orders' => CacheService::invalidateOrders(),
                'carts' => Cache::tags(['carts'])->flush(),
                default => throw new \Exception("Invalid cache type: {$type}"),
            };

            return "✓ {$type} caches cleared successfully";
        } catch (\Exception $e) {
            return "✗ Error clearing caches: " . $e->getMessage();
        }
    }
}
