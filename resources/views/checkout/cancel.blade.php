<x-app-layout>

    <!-- Order Cancelled Page -->
    <div class="container mx-auto px-6 py-20 text-center">
        <!-- Cancel Icon -->
        <div class="mx-auto w-16 h-16 flex items-center justify-center rounded-full bg-red-100 text-red-600 mb-6">
            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M6 18L18 6M6 6l12 12" />
            </svg>
        </div>

        <!-- Message -->
        <h1 class="text-2xl font-bold text-gray-900 mb-2">Order Cancelled</h1>
        <p class="text-gray-600 mb-6 max-w-xl mx-auto">
            Your checkout process was cancelled and no payment has been made.
            You can return to your cart or continue browsing our collection whenever you’re ready.
        </p>

        <!-- Order Info -->
        <div class="max-w-lg mx-auto bg-white border border-gray-100 shadow-sm rounded-lg p-6 text-left mb-8">
            <h2 class="text-lg font-semibold text-gray-900 mb-4">What Happens Next?</h2>
            <ul class="space-y-3 text-sm text-gray-700">
            <li class="flex items-start">
                <span class="text-gray-400 mr-2">•</span>
                Items remain in your cart unless removed manually.
            </li>
            <li class="flex items-start">
                <span class="text-gray-400 mr-2">•</span>
                You can complete your purchase anytime.
            </li>
            <li class="flex items-start">
                <span class="text-gray-400 mr-2">•</span>
                No charges were processed.
            </li>
            </ul>
        </div>

        <!-- Actions -->
        <div class="flex justify-center space-x-4">
            <a href="{{ route('buyer.cart') }}"
            class="bg-black text-white px-6 py-2 rounded-md shadow-sm hover:brightness-95">
            Return to Cart
            </a>
            <a href="{{ route('dashboard') }}"
            class="border border-gray-300 px-6 py-2 rounded-md text-gray-700 hover:bg-gray-50">
            Continue Shopping
            </a>
        </div>
    </div>

</x-app-layout>