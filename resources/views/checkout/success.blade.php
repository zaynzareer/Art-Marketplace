<x-app-layout>

  <div class="container mx-auto px-6 py-20 text-center">
    <!-- Success Icon -->
    <div class="mx-auto w-16 h-16 flex items-center justify-center rounded-full bg-green-100 text-green-600 mb-6">
        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
            d="M5 13l4 4L19 7" />
        </svg>
    </div>

    <!-- Confirmation Text -->
    <h1 class="text-2xl font-bold text-gray-900 mb-2">Thank You for Your Order!</h1>
    <p class="text-gray-600 mb-6">
        Your order has been successfully placed. An email confirmation has been sent to {{ auth()->user()->email }}
    </p>

    <!-- Actions -->
    <div class="mt-8 flex justify-center space-x-4">
        <a href="{{ route('dashboard') }}"
        class="bg-black text-white px-6 py-2 rounded-md shadow-sm hover:brightness-95">
        Continue Shopping
        </a>
        <a href="{{ route('buyer.orders') }}"
        class="border border-gray-300 px-6 py-2 rounded-md text-gray-700 hover:bg-gray-50">
        View My Orders
        </a>
    </div>
  </div>

</x-app-layout>