<div class="container mx-auto px-4 sm:px-6 py-6 md:py-12">
    <div class="max-w-6xl mx-auto">

        <h1 class="text-xl sm:text-2xl font-bold text-gray-900 mb-4 md:mb-6">Shopping Cart</h1>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 md:gap-8">

            {{-- Cart items --}}
            <section class="lg:col-span-8">
                <div class="bg-white border rounded-lg shadow-sm p-3 sm:p-4 space-y-4">

                    {{-- Header --}}
                    <div class="hidden md:flex text-xs sm:text-sm text-gray-500 px-3 py-2 border-b">
                        <div class="flex-1">Product</div>
                        <div class="w-32 lg:w-36 text-right">Price</div>
                        <div class="w-32 lg:w-36 text-center">Quantity</div>
                        <div class="w-32 lg:w-36 text-right"></div>
                    </div>

                    @if (empty($items))
                        <div class="p-6 text-center text-sm sm:text-base text-gray-600">
                            Your cart is empty.
                            <a href="{{ route('dashboard') }}"
                               class="text-black font-semibold hover:underline">
                                Browse products
                            </a>
                        </div>
                    @else
                        @foreach ($items as $item)
                            <div class="flex flex-col md:flex-row md:items-center p-2 sm:p-3 rounded-md hover:bg-gray-50 transition-opacity duration-200"
                                 wire:target="removeItem({{ $item['product_id'] }})"
                                 wire:loading.class="opacity-50 cursor-not-allowed"
                                 wire:loading.attr="disabled">

                                {{-- Product --}}
                                <div class="flex items-start space-x-3 sm:space-x-4 md:flex-1">
                                    <img src="{{ Storage::url($item['image']) }}"
                                         class="w-20 h-16 sm:w-24 sm:h-20 object-cover rounded-md border flex-shrink-0">
                                    <div class="flex-1 min-w-0">
                                        <p class="text-sm font-semibold text-gray-900 truncate">
                                            {{ $item['name'] }}
                                        </p>
                                        <p class="text-xs text-gray-500 truncate">
                                            Category: {{ $item['category'] }}
                                        </p>
                                    </div>
                                </div>

                                {{-- Price --}}
                                <div class="mt-3 md:mt-0 md:w-32 lg:w-36 flex justify-between md:block md:text-right">
                                    <span class="text-xs text-gray-500 md:hidden">Price:</span>
                                    <div>
                                        <div class="text-sm font-semibold">
                                            ${{ $item['price'] }}
                                        </div>
                                        <div class="text-xs text-gray-500 hidden md:block">Unit price</div>
                                    </div>
                                </div>

                                {{-- Quantity --}}
                                <div class="mt-3 md:mt-0 md:w-32 lg:w-36 flex justify-between md:block md:flex md:justify-center">
                                    <span class="text-xs text-gray-500 md:hidden">Quantity:</span>
                                    <input type="number"
                                           min="1"
                                           wire:change="updateQuantity({{ $item['product_id'] }}, $event.target.value)"
                                           value="{{ $item['quantity'] }}"
                                           class="w-14 text-center border rounded-md py-1 text-sm">
                                </div>

                                {{-- Remove --}}
                                <div class="mt-3 md:mt-0 md:w-32 lg:w-36 md:text-right">
                                    <button wire:click="removeItem({{ $item['product_id'] }})"
                                            class="text-xs sm:text-sm text-red-600 hover:bg-red-50 px-2 py-1 rounded-md inline-flex items-center transition-colors">
                                        <svg class="w-4 h-4 mr-1 sm:mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                  d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7"/>
                                        </svg>
                                        Remove
                                    </button>
                                </div>

                            </div>
                        @endforeach
                    @endif

                </div>
            </section>

            {{-- Order summary --}}
            <aside class="lg:col-span-4">
                <div class="bg-white border rounded-lg shadow-sm p-4 sm:p-6 sticky top-4">
                    <h2 class="text-base sm:text-lg font-semibold mb-4">Order Summary</h2>

                    <div class="flex justify-between text-sm">
                        <span>Subtotal</span>
                        <span class="font-semibold">${{ $subtotal }}</span>
                    </div>

                    <button class="w-full bg-black text-white py-3 mt-6 rounded-md text-sm font-semibold hover:bg-gray-800 transition-colors {{ empty($items) ? 'opacity-50' : '' }}"
                        wire:click="checkout"
                        wire:target="checkout"
                        wire:loading.class="opacity-70 cursor-not-allowed"
                        @if (empty($items)) disabled @endif
                    >
                        <span wire:loading.remove wire:target="checkout">
                            Proceed to Checkout
                        </span>
                        <span wire:loading wire:target="checkout" class="flex items-center justify-center">
                            Processing...
                        </span>
                    </button>

                    <a href="{{ route('products.index') }}"
                       class="block text-center text-sm text-gray-600 mt-3 hover:underline">
                        Continue shopping
                    </a>
                </div>
            </aside>

        </div>
    </div>
</div>
