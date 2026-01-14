<div class="container mx-auto px-6 py-10">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Your Products</h1>
            <p class="text-sm text-gray-500 mt-1">Manage listings visible on your store</p>
        </div>

        <a href="{{ route('products.create') }}"
           class="inline-flex items-center bg-black text-white px-4 py-2 rounded-md shadow-sm hover:brightness-95">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M12 4v16m8-8H4"/>
            </svg>
            Add Product
        </a>
    </div>

    <!-- Filters -->
    <div class="flex items-center justify-between mb-6 gap-4">
        <div class="flex items-center space-x-3 w-full sm:w-2/3">
            <input
                type="text"
                wire:model.live="search"
                placeholder="Search title..."
                class="w-full border border-gray-200 rounded-md px-4 py-2 text-sm focus:ring-black"
            />

            <select wire:model.live="category"
                    class="border border-gray-200 rounded-md px-3 py-2 text-sm">
                <option value="all">All categories</option>
                <option value="Paintings">Paintings</option>
                <option value="Sculpture">Sculpture</option>
                <option value="Prints">Prints</option>
            </select>
        </div>

        <div class="text-sm text-gray-500">
            Showing <span class="font-medium text-gray-700">{{ $meta['total'] ?? 0  }}</span> products
        </div>
    </div>

    <!-- Empty State -->
    @if (empty($products))
        <x-seller.empty-state message="You have no products listed!" />
    @else
        <!-- Products Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
            @foreach ($this->products as $product)
                <x-seller.product-card
                    :product="$product"
                    wire:key="product-{{ $product['id'] }}"
                />
            @endforeach
        </div>
    @endif

    {{-- Pagination buttons --}}
    @if (!empty($meta))
        <div class="mt-8 flex justify-center space-x-1">
            {{-- Previous --}}
            <button
                wire:click="gotoPage({{ $meta['current_page'] - 1 }})"
                @disabled($meta['current_page'] === 1)
                class="px-3 py-1 border rounded disabled:opacity-50"
            >
                Prev
            </button>

            {{-- Page numbers --}}
            @for ($i = 1; $i <= $meta['last_page']; $i++)
                <button
                    wire:click="gotoPage({{ $i }})"
                    class="px-3 py-1 border rounded
                        {{ $meta['current_page'] === $i ? 'bg-black text-white' : 'bg-gray-100' }}"
                >
                    {{ $i }}
                </button>
            @endfor

            {{-- Next --}}
            <button
                wire:click="gotoPage({{ $meta['current_page'] + 1 }})"
                @disabled($meta['current_page'] === $meta['last_page'])
                class="px-3 py-1 border rounded disabled:opacity-50"
            >
                Next
            </button>
        </div>
    @endif
</div>

