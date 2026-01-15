<x-app-layout>
    <div class="max-w-2xl mx-auto py-8">
        <h1 class="text-2xl font-bold mb-6">Create Product</h1>
    
        <livewire:seller.product-form :productId="$productId ?? null" />

    </div>
</x-app-layout>