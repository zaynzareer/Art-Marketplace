<div class="bg-white rounded-lg shadow-sm overflow-hidden border border-gray-100 max-w-xs">
    <div class="w-full h-44 bg-gray-50">
        <img src="{{ Storage::url($product['image']) }}" class="w-full h-full object-cover">
    </div>

    <div class="p-4">
        <h3 class="text-sm font-semibold text-gray-900 truncate">
            {{ $product['name'] }}
        </h3>

        <p class="text-xs text-gray-500 mt-1">
            By {{ $product['seller']['name'] }}
        </p>

        <div class="mt-3 flex items-center justify-between">
            <p class="text-sm font-bold text-gray-900">
                ${{ $product['price'] }}
            </p>

            <div class="flex gap-2">
                <a
                    href="{{ route('buyer.products.show', $product['id']) }}"
                    class="border border-gray-300 text-gray-700 text-sm px-3 py-1 rounded-md hover:bg-gray-50 transition"
                >
                    View
                </a>

                <button
                    wire:click="addToCart({{ $product['id'] }})"
                    wire:target="addToCart({{ $product['id'] }})"
                    wire:loading.class="opacity-70 cursor-not-allowed"
                    class="bg-black text-white text-sm px-3 py-1 rounded-md"
                >
                    Add
                </button>
            </div>
        </div>
    </div>
</div>
