<div class="container mx-auto px-6 py-12">
    <h1 class="text-2xl font-bold text-gray-900 mb-8">My Orders</h1>

    @forelse ($ordersByDate as $period => $orders)
        <!-- Period Group -->
        <div class="mb-10">
            <h2 class="text-sm font-semibold text-gray-500 uppercase mb-4">
                {{ $period }}
            </h2>

            <div class="space-y-6">
                @foreach ($orders as $order)
                    <div class="bg-white border border-gray-100 rounded-lg shadow-sm p-6">
                        <!-- Header -->
                        <div class="flex justify-between items-center mb-4">
                            <div>
                                <p class="text-sm text-gray-600">
                                    Order #: <span class="font-medium text-gray-900">#{{ $order['id'] }}</span>
                                </p>
                                <p class="text-sm text-gray-600">
                                    Placed on:
                                    <span class="font-medium text-gray-900">
                                        {{ \Carbon\Carbon::parse($order['order_date'])->format('M d, Y') }}
                                    </span>
                                </p>
                                @if(isset($order['seller_name']))
                                    <p class="text-sm text-gray-600">
                                        Seller:
                                        <span class="font-medium text-gray-900">
                                            {{ $order['seller_name'] }}
                                        </span>
                                    </p>
                                @endif
                            </div>

                            {{-- Status Badge --}}
                            @php
                                $statusClasses = match ($order['status']) {
                                    'processing' => 'bg-yellow-100 text-yellow-700',
                                    'shipped'    => 'bg-blue-100 text-blue-700',
                                    'delivered'  => 'bg-green-100 text-green-700',
                                    'cancelled'  => 'bg-red-100 text-red-700',
                                    default      => 'bg-gray-100 text-gray-700',
                                };
                            @endphp

                            <span class="px-3 py-1 text-xs font-semibold rounded-full {{ $statusClasses }}">
                                {{ ucfirst($order['status']) }}
                            </span>
                        </div>

                        <!-- Products -->
                        <div class="divide-y">
                            @foreach ($order['order_items'] as $item)
                                <div class="flex items-center py-4">
                                    <img
                                        src="{{ Storage::url($item['image']) }}"
                                        alt="{{ $item['name'] }}"
                                        class="w-20 h-20 object-cover rounded-md border"
                                    >

                                    <div class="ml-4 flex-1">
                                        <h3 class="text-sm font-semibold text-gray-900">
                                            {{ $item['name'] }}
                                        </h3>
                                        <p class="text-xs text-gray-500">
                                            Category: {{ $item['category'] }}
                                        </p>
                                        <p class="text-xs text-gray-500">
                                            Qty: {{ $item['quantity'] }}
                                        </p>
                                    </div>

                                    <div class="text-sm font-semibold text-gray-900">
                                        ${{ number_format($item['price'] * $item['quantity'], 2) }}
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <!-- Footer -->
                        <div class="mt-4 flex justify-between items-center">
                            <p class="text-sm text-gray-700">
                                Total:
                                <span class="font-semibold text-gray-900">
                                    ${{ number_format($order['total'], 2) }}
                                </span>
                            </p>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @empty
        <div class="bg-white border border-gray-100 rounded-lg shadow-sm p-12 text-center">
            <p class="text-gray-500 mb-4">No orders yet...</p>
            <a href="{{ route('dashboard') }}"
                class="text-black font-semibold hover:underline">
                Browse products
            </a>
        </div>
    @endforelse
</div>
