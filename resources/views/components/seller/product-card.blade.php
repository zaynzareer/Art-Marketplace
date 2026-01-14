@props(['product'])

<div class="bg-white rounded-lg shadow-sm overflow-hidden border border-gray-100 max-w-xs">
    <img src="{{ asset('storage/' . $product['image']) }}" alt="{{ $product['name'] }}" class="w-full object-cover">

    <div class="p-4">
        <div class="flex justify-between">
            <div class="min-w-0">
                <h3 class="text-sm font-semibold text-gray-900 truncate">{{ $product['name'] }}</h3>
                <p class="text-xs text-gray-500 mt-1 truncate">{{ $product['category'] }}</p>
            </div>

            <p class="text-sm font-bold text-gray-900">${{ $product['price'] }}</p>
        </div>

        <div class="mt-4 flex justify-between">
            <a href="{{ route('products.edit', $product['id']) }}"
               class="text-sm text-gray-600 hover:underline">
                Edit
            </a>

            <button
                wire:click="deleteProduct({{ $product['id'] }})"
                wire:confirm="Delete this product?"
                class="inline-flex items-center text-sm text-red-600 hover:bg-red-50 px-3 py-1 rounded-md">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7"/>
                </svg>
                Delete
            </button>
        </div>
    </div>
</div>
