<div class="container mx-auto px-4 sm:px-6 py-6 md:py-10">
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 lg:gap-8">

        {{-- Sidebar: Categories --}}
        <aside class="lg:col-span-3">
            {{-- Mobile: Dropdown --}}
            <div class="lg:hidden mb-6">
                <label for="mobile-category" class="block text-sm font-semibold text-gray-900 mb-2">Category</label>
                <select
                    id="mobile-category"
                    wire:model.live="category"
                    class="w-full border border-gray-200 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-1 focus:ring-black focus:border-black"
                >
                    @foreach ($categories as $key => $label)
                        <option value="{{ $key }}">{{ $label }}</option>
                    @endforeach
                </select>
            </div>

            {{-- Desktop: Sidebar --}}
            <div class="hidden lg:block bg-white border border-gray-100 rounded-xl shadow-sm p-4">
                <h2 class="text-sm font-semibold text-gray-900 mb-3">Category</h2>
                <nav class="space-y-1">
                    @foreach ($categories as $key => $label)
                        <a href="#"
                            wire:click.prevent="$set('category', '{{ $key }}')"
                            class="block px-3 py-2 rounded-lg text-sm transition-colors
                                {{ $category === $key
                                    ? 'bg-black text-white'
                                    : 'text-gray-600 hover:bg-gray-50' }}"
                        >
                            {{ $label }}
                        </a>
                    @endforeach    
                </nav>
            </div>
        </aside>

        {{-- Main content --}}
        <main class="lg:col-span-9">

            {{-- Search + controls --}}
            <div class="mb-6 space-y-4 md:space-y-0">
                {{-- Search bar (centered on mobile) --}}
                <div class="flex justify-center mb-4">
                    <div class="relative w-full max-w-2xl">
                        <label for="search" class="sr-only">Search products</label>
                        <input
                            id="search"
                            type="search"
                            placeholder="Search products..."
                            wire:model.live="search"
                            wire:loading.class="opacity-50"
                            class="w-full border border-gray-200 rounded-lg px-4 py-2.5 pr-10 text-sm focus:outline-none focus:ring-2 focus:ring-black focus:border-black transition-shadow"
                        />
                        <svg class="w-5 h-5 text-gray-400 absolute right-3 top-1/2 -translate-y-1/2 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M21 21l-4.35-4.35M17 11A6 6 0 115 11a6 6 0 0112 0z"/>
                        </svg>
                    </div>
                </div>

                {{-- Sort dropdown --}}
                <div class="flex justify-end">
                    <div>
                        <label for="sort" class="sr-only">Sort</label>
                        <select
                            id="sort"
                            wire:model.live="sort"
                            class="border border-gray-200 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-1 focus:ring-black focus:border-black w-full sm:w-auto"
                        >
                            <option value="newest">Newest</option>
                            <option value="price_asc">Price: Low → High</option>
                            <option value="price_desc">Price: High → Low</option>
                        </select>
                    </div>
                </div>
            </div>

            {{-- Empty state --}}
            @if (empty($this->products))
                <div class="flex items-center p-4 mb-4 text-sm text-blue-800 rounded-lg bg-blue-50" role="alert">
                    <svg class="shrink-0 inline w-4 h-4 me-3" aria-hidden="true"
                         xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                        <path
                            d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5ZM9.5 4a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3ZM12 15H8a1 1 0 0 1 0-2h1v-3H8a1 1 0 0 1 0-2h2a1 1 0 0 1 1 1v4h1a1 1 0 0 1 0 2Z"/>
                    </svg>
                    <span class="sr-only">Info</span>
                    <div>There are no products at the moment!</div>
                </div>
            @else
                <div wire:loading.delay>
                    <div class="mb-4 text-center text-gray-500">
                        Loading products...
                    </div>
                </div>
                {{-- Products grid --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-4 md:gap-6">
                    @foreach ($this->products as $product)
                        {{-- Product card --}}
                        <x-buyer.product-card :product="$product" />
                    @endforeach
                </div>
            @endif

            {{-- Pagination buttons --}}
            @if (!empty($meta))
                <div class="mt-8 flex flex-wrap justify-center gap-1 sm:gap-2">
                    {{-- Previous --}}
                    <button
                        wire:click="gotoPage({{ $meta['current_page'] - 1 }})"
                        @disabled($meta['current_page'] === 1)
                        class="px-3 py-2 text-sm border rounded disabled:opacity-50 disabled:cursor-not-allowed hover:bg-gray-50 transition-colors"
                    >
                        Prev
                    </button>

                    {{-- Page numbers --}}
                    @for ($i = 1; $i <= $meta['last_page']; $i++)
                        <button
                            wire:click="gotoPage({{ $i }})"
                            class="px-3 py-2 text-sm border rounded transition-colors
                                {{ $meta['current_page'] === $i ? 'bg-black text-white' : 'bg-white hover:bg-gray-50' }}"
                        >
                            {{ $i }}
                        </button>
                    @endfor

                    {{-- Next --}}
                    <button
                        wire:click="gotoPage({{ $meta['current_page'] + 1 }})"
                        @disabled($meta['current_page'] === $meta['last_page'])
                        class="px-3 py-2 text-sm border rounded disabled:opacity-50 disabled:cursor-not-allowed hover:bg-gray-50 transition-colors"
                    >
                        Next
                    </button>
                </div>
            @endif

        </main>
    </div>
</div>