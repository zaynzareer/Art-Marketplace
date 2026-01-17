<?php

namespace App\Console\Commands;

use App\Models\Product;
use App\Services\CacheService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;

class TestCaching extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cache:test {--full : Run full test suite}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test caching system functionality';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $this->info('🧪 Testing Cache System...');
        $this->line('');

        // Test 1: Connection
        if (!$this->testConnection()) {
            return 1;
        }

        // Test 2: Basic caching
        if (!$this->testBasicCaching()) {
            return 1;
        }

        // Test 3: Tags and invalidation
        if (!$this->testTags()) {
            return 1;
        }

        // Test 4: Service methods
        if (!$this->testCacheService()) {
            return 1;
        }

        if ($this->option('full')) {
            // Test 5: Model observers
            if (!$this->testModelObservers()) {
                return 1;
            }
        }

        $this->info('');
        $this->info('✅ All cache tests passed!');
        return 0;
    }

    /**
     * Test cache connection
     */
    private function testConnection(): bool
    {
        $this->line('Test 1/4: Cache Connection');

        try {
            Cache::put('cache:test', 'value', 1);
            $value = Cache::get('cache:test');

            if ($value === 'value') {
                $this->info('  ✓ Cache connection: OK');
                Cache::forget('cache:test');
                return true;
            }

            $this->error('  ✗ Cache connection: FAILED (no value returned)');
            return false;
        } catch (\Exception $e) {
            $this->error('  ✗ Cache connection: FAILED');
            $this->error('    Error: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Test basic caching operations
     */
    private function testBasicCaching(): bool
    {
        $this->line('Test 2/4: Basic Caching Operations');

        try {
            // Test put/get
            Cache::put('test:key', 'test:value', 60);
            $retrieved = Cache::get('test:key');

            if ($retrieved !== 'test:value') {
                $this->error('  ✗ Put/Get: FAILED');
                return false;
            }
            $this->info('  ✓ Put/Get operations: OK');

            // Test remember
            $remembered = Cache::remember('test:remember', 60, fn() => 'remembered:value');
            if ($remembered !== 'remembered:value') {
                $this->error('  ✗ Remember: FAILED');
                return false;
            }
            $this->info('  ✓ Remember operation: OK');

            // Test forget
            Cache::forget('test:key');
            if (Cache::has('test:key')) {
                $this->error('  ✗ Forget: FAILED');
                return false;
            }
            $this->info('  ✓ Forget operation: OK');

            return true;
        } catch (\Exception $e) {
            $this->error('  ✗ Basic operations: FAILED');
            $this->error('    Error: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Test cache tags
     */
    private function testTags(): bool
    {
        $this->line('Test 3/4: Cache Tags & Invalidation');

        try {
            // Set values with tags
            Cache::tags(['test', 'products'])->put('test:1', 'value1', 60);
            Cache::tags(['test', 'products'])->put('test:2', 'value2', 60);
            Cache::tags(['test', 'sellers'])->put('test:3', 'value3', 60);

            // Verify they're stored (must use same tags to retrieve)
            if (Cache::tags(['test', 'products'])->get('test:1') !== 'value1') {
                $this->error('  ✗ Tag storage: FAILED');
                return false;
            }
            $this->info('  ✓ Tag storage: OK');

            // Flush by tag
            Cache::tags(['products'])->flush();

            // Check products flushed (must use tags to check)
            $val1 = Cache::tags(['test', 'products'])->get('test:1');
            $val2 = Cache::tags(['test', 'products'])->get('test:2');
            $val3 = Cache::tags(['test', 'sellers'])->get('test:3');

            if ($val1 !== null || $val2 !== null) {
                $this->error('  ✗ Tag flush: FAILED (product cache not flushed)');
                return false;
            }

            if ($val3 !== 'value3') {
                $this->error('  ✗ Tag flush: FAILED (unrelated cache was flushed)');
                return false;
            }

            $this->info('  ✓ Tag flush/invalidation: OK');

            // Cleanup
            Cache::tags(['test', 'sellers'])->flush();
            return true;
        } catch (\Exception $e) {
            $this->error('  ✗ Tags: FAILED');
            $this->error('    Error: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Test CacheService methods
     */
    private function testCacheService(): bool
    {
        $this->line('Test 4/4: CacheService Methods');

        try {
            // Test cache availability
            $available = CacheService::isAvailable();
            if (!$available) {
                $this->error('  ✗ Cache availability check: FAILED');
                return false;
            }
            $this->info('  ✓ Cache availability: OK');

            // Test invalidation methods exist and don't throw
            CacheService::invalidateProducts();
            CacheService::invalidateSeller(1);
            CacheService::invalidateCart(1);
            CacheService::invalidateOrders();
            $this->info('  ✓ Invalidation methods: OK');

            return true;
        } catch (\Exception $e) {
            $this->error('  ✗ CacheService: FAILED');
            $this->error('    Error: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Test model observers
     */
    private function testModelObservers(): bool
    {
        $this->line('Test 5/5: Model Observers');

        $this->warn('  ⚠ Skipped (requires test database setup)');
        return true;
    }
}
