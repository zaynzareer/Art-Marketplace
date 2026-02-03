@props(['order', 'onStatusChange'])

<div class="bg-white border rounded-lg shadow-sm p-4 sm:p-6">
    {{-- Header --}}
    <div class="flex flex-col md:flex-row md:justify-between md:items-start gap-3 md:gap-0 mb-6">
        <div class="space-y-1 min-w-0">
            <p class="text-xs sm:text-sm text-gray-600 truncate">
                Order:
                <span class="font-medium text-gray-900">
                    #{{ $order['id'] }}
                </span>
            </p>
            <p class="text-xs sm:text-sm text-gray-600 truncate">
                Buyer:
                <span class="font-medium text-gray-900 truncate">
                    {{ $order['buyer_name'] }}
                </span>
            </p>
            <p class="text-xs sm:text-sm text-gray-600">
                Placed on:
                <span class="font-medium">
                    {{ \Carbon\Carbon::parse($order['order_date'])->format('M d, Y') }}
                </span>
            </p>
        </div>

        {{-- Status --}}
        <div class="mt-4 md:mt-0">
            {{ $onStatusChange }}
        </div>
    </div>

    {{-- Items --}}
    <div class="divide-y">
        @foreach ($order['order_items'] as $item)
            <x-seller.order-item :item="$item" />
        @endforeach
    </div>

    {{-- Footer --}}
    <div class="mt-6 flex flex-col sm:flex-row sm:justify-between gap-2">
        <p class="text-xs sm:text-sm">
            Order Total:
            <span class="font-semibold">
                ${{ $order['total'] }}
            </span>
        </p>
    </div>
</div>
