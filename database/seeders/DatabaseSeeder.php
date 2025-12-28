<?php

namespace Database\Seeders;

use App\Models\Cart;
use App\Models\User;
use App\Models\Order;
use App\Models\Product;
use App\Models\CartItem;
use App\Models\OrderItem;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // $user = User::factory(10)->create();

        // // User::factory()->create([
        // //     'name' => 'Test User',
        // //     'email' => 'test@example.com',
        // // ]);

        // Product::factory(10)->for($user)->create();

        User::factory(10)->create();

        Product::factory(20)->create();

        Cart::factory(8)->create();

        Order::factory(15)->create();

        OrderItem::factory(40)->create();

        CartItem::factory(30)->create();
    }
}
