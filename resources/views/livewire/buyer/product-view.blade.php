<div class="container mx-auto px-6 py-12 max-w-6xl">
    <!-- Back -->
    <a href="{{ route('dashboard') }}"
       class="inline-flex items-center text-sm text-gray-600 hover:underline mb-6">
        ← Back to products
    </a>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- Image -->
        <div class="bg-white border rounded-lg overflow-hidden shadow-sm">
            <img
                src="{{ asset('storage/' . $product['image']) }}"
                alt="{{ $product['name'] }}"
                class="w-full h-[420px] object-cover"
            >
        </div>

        <!-- Details -->
        <div class="bg-white border rounded-lg shadow-sm p-6 flex flex-col justify-between">
            <div>
                <span class="inline-block text-xs px-3 py-1 rounded-full bg-gray-100 mb-3">
                    {{ $product['category'] }}
                </span>

                <h1 class="text-3xl font-semibold text-gray-900">
                    {{ $product['name'] }}
                </h1>

                <div class="mt-4">
                    <span class="text-2xl font-bold">
                        ${{ number_format($product['price'], 2) }}
                    </span>
                    <p class="text-sm text-gray-500 mt-1">
                        Ships in 3–5 business days
                    </p>
                </div>

                <div class="mt-6">
                    <h3 class="text-sm font-medium mb-2">Description</h3>
                    <p class="text-sm text-gray-700 leading-relaxed">
                        {{ $product['description'] }}
                    </p>
                </div>
            </div>

            <!-- Purchase -->
            <div class="mt-6 space-y-4">
                <div class="inline-flex items-center border rounded-md overflow-hidden">
                    <button wire:click="decrementQty"
                            class="px-3 py-2 hover:bg-gray-50">−</button>

                    <input
                        type="number"
                        wire:model.defer="quantity"
                        class="w-16 text-center border-l border-r focus:outline-none"
                    >

                    <button wire:click="incrementQty"
                            class="px-3 py-2 hover:bg-gray-50">+</button>
                </div>

                <button
                    wire:click="addToCart"
                    class="block w-full bg-black text-white py-3 rounded-md text-sm font-semibold hover:brightness-95"
                >
                    Add to Cart
                </button>
            </div>
        </div>
    </div>

    <!-- Seller Info -->
    <div class="mt-8 bg-white border rounded-lg shadow-sm p-6 flex items-center gap-4">
        <div class="p-2 rounded-full bg-gray-100">
            👤
        </div>

        <div>
            <p class="text-sm font-semibold">
                {{ $product['seller']['name'] }}
            </p>
            <p class="text-sm text-gray-500">
                Member since {{ $product['seller']['seller_since'] ?? '2026' }}
            </p>
        </div>
    </div>

    <!-- Related Products -->
    <section class="mt-10">
        <h2 class="text-lg font-semibold mb-4">You may also like</h2>

        @if (empty($sellerProducts))
            <div class="text-sm text-gray-600 bg-blue-50 p-4 rounded-lg">
                No other products from this seller yet.
            </div>
        @else
            <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-6">
                @foreach ($sellerProducts as $item)
                    @continue($item['product_id'] === $product['product_id'])

                    <x-buyer.related-card :product="$item" />
                @endforeach
            </div>
        @endif
    </section>
</div>
