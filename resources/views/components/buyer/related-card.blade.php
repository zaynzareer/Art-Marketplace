<a href="{{ route('buyer.products.show', $product['product_id']) }}"
   class="block bg-white border rounded-lg overflow-hidden shadow-sm hover:shadow transition">
    <div class="h-40 bg-gray-50">
        <img src="{{ Storage::url($product['image']) }}"
             alt="{{ $product['name'] }}"
             class="w-full h-full object-cover">
    </div>

    <div class="p-3">
        <h3 class="text-sm font-semibold truncate">
            {{ $product['name'] }}
        </h3>
        <p class="text-xs text-gray-500 mt-1">
            ${{ number_format($product['price'], 2) }}
        </p>
    </div>
</a>
