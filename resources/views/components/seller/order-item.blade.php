@props(['item'])

<div class="flex items-center py-4">
    <img src="{{ Storage::url($item['image']) }}"
         class="w-20 h-20 object-cover rounded-md border">

    <div class="ml-4 flex-1">
        <h3 class="text-sm font-semibold">{{ $item['name'] }}</h3>
        <p class="text-xs text-gray-500">
            Category: {{ $item['category'] }}
        </p>
        <p class="text-xs text-gray-500">
            Quantity: {{ $item['quantity'] }}
        </p>
    </div>

    <div class="text-sm font-semibold">
        ${{ $item['price'] }}
    </div>
</div>
