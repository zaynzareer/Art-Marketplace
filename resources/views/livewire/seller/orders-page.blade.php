<div class="container mx-auto px-6 py-12">
    <h1 class="text-2xl font-bold mb-8">Customer Orders</h1>

    @forelse ($ordersByDate as $label => $orders)
        <div class="mb-10">
            <h2 class="text-lg font-semibold text-gray-700 mb-4">
                {{ $label }}
            </h2>

            <div class="space-y-6">
                @foreach ($orders as $order)
                    <x-seller.order-card
                        :order="$order"
                        wire:key="order-{{ $order['id'] }}"
                    >
                        <x-slot name="onStatusChange">
                            @if ($order['status'] === 'delivered')
                                <span class="px-3 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-700">
                                    Delivered
                                </span>
                            @else
                                <select
                                    wire:change="updateStatus({{ $order['id'] }}, $event.target.value)"
                                    class="border rounded-md px-4 py-2 text-sm">
                                    <option value="pending" @selected($order['status'] === 'pending')>
                                        Pending
                                    </option>
                                    <option value="processing" @selected($order['status'] === 'processing')>
                                        Processing
                                    </option>
                                    <option value="shipped" @selected($order['status'] === 'shipped')>
                                        Shipped
                                    </option>
                                    <option value="delivered" @selected($order['status'] === 'delivered')>
                                        Delivered
                                    </option>
                                </select>
                            @endif
                        </x-slot>
                    </x-seller.order-card>
                @endforeach
            </div>
        </div>
    @empty
        <p class="text-gray-600">No orders yet.</p> 
        
    @endforelse
</div>
