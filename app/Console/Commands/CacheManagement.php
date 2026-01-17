<?php

namespace App\Console\Commands;

use App\Services\CacheService;
use Illuminate\Console\Command;

class CacheManagement extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cache:manage {action : The action to perform (clear-all, clear-products, clear-orders, clear-carts, status)}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Manage application caches (clear, invalidate, status)';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $action = $this->argument('action');

        match ($action) {
            'clear-all' => $this->clearAllCaches(),
            'clear-products' => $this->clearProducts(),
            'clear-orders' => $this->clearOrders(),
            'clear-carts' => $this->clearCarts(),
            'status' => $this->showStatus(),
            default => $this->error("Unknown action: {$action}")
        };

        return 0;
    }

    /**
     * Clear all application caches
     */
    private function clearAllCaches(): void
    {
        $this->warn('Clearing all application caches...');
        CacheService::clearAll();
        $this->info('✓ All caches cleared successfully');
    }

    /**
     * Clear product caches
     */
    private function clearProducts(): void
    {
        $this->warn('Clearing product caches...');
        CacheService::invalidateProducts();
        $this->info('✓ Product caches cleared');
    }

    /**
     * Clear order caches
     */
    private function clearOrders(): void
    {
        $this->warn('Clearing order caches...');
        CacheService::invalidateOrders();
        $this->info('✓ Order caches cleared');
    }

    /**
     * Clear cart caches
     */
    private function clearCarts(): void
    {
        $this->warn('Clearing cart caches...');
        CacheService::clearAll();
        $this->info('✓ Cart caches cleared');
    }

    /**
     * Show cache status
     */
    private function showStatus(): void
    {
        $this->info('Cache Status:');
        $this->line('');
        
        if (CacheService::isAvailable()) {
            $this->info('✓ Cache system: OPERATIONAL');
        } else {
            $this->error('✗ Cache system: UNAVAILABLE');
        }

        $this->table(
            ['Configuration', 'Value'],
            [
                ['Driver', config('cache.default')],
                ['Default TTL (Products)', CacheService::PRODUCT_LIST_TTL . 's (~' . round(CacheService::PRODUCT_LIST_TTL / 60) . ' min)'],
                ['Default TTL (Metrics)', CacheService::SELLER_METRICS_TTL . 's (~' . round(CacheService::SELLER_METRICS_TTL / 60) . ' min)'],
                ['Fallback Stores', 'Redis → Database → Array'],
            ]
        );

        $this->line('');
        $this->info('Available Commands:');
        $this->line('  php artisan cache:manage clear-all       - Clear all caches');
        $this->line('  php artisan cache:manage clear-products  - Clear product caches');
        $this->line('  php artisan cache:manage clear-orders    - Clear order caches');
        $this->line('  php artisan cache:manage clear-carts     - Clear cart caches');
    }
}
