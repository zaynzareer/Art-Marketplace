@props(['orders'])

<div class="bg-white rounded-xl shadow p-6">
    <h2 class="text-lg font-semibold text-gray-900 mb-4">
        Recent Orders
    </h2>

    <ul class="divide-y divide-gray-200">
        @forelse ($orders as $order)
            @php
                $items = $order['order_items'] ?? $order['items'] ?? $order['orderItems'] ?? [];
                $firstItem = $items[0] ?? [];
                $product = $firstItem['product'] ?? [];

                $image = $product['image'] ?? null;
                if ($image) {
                    $image = str_starts_with($image, 'http') || str_starts_with($image, '/storage/')
                        ? $image
                        : Storage::url(ltrim($image, '/'));
                } else {
                    $image = asset('images/placeholder.png');
                }

                $name = $product['name'] ?? ('Order #' . ($order['id'] ?? ''));
                $createdAt = $order['order_date'] ?? $order['created_at'] ?? null;
                try {
                    $timeText = $createdAt ? \Carbon\Carbon::parse($createdAt)->diffForHumans() : '';
                } catch (\Throwable $e) {
                    $timeText = $createdAt ?? '';
                }

                $amount = 0;
                if (isset($order['total'])) {
                    $amount = (float) $order['total'];
                } else {
                    foreach ($items as $it) {
                        $price = (float)($it['price'] ?? $it['unit_price'] ?? 0);
                        $qty = (int)($it['quantity'] ?? 1);
                        $amount += $price * $qty;
                    }
                }

                $status = $order['status'] ?? null;
                $statusLower = $status ? strtolower($status) : '';
                $statusClass = match ($statusLower) {
                    'delivered', 'completed', 'paid' => 'text-green-600',
                    'processing' => 'text-yellow-600',
                    'shipped' => 'text-blue-600',
                    'pending' => 'text-orange-600',
                    'failed', 'cancelled' => 'text-red-600',
                    default => 'text-gray-500',
                };
            @endphp

            <li class="flex items-center justify-between py-4">
                <div class="flex items-center space-x-4">
                    <img
                        src="{{ $image }}"
                        class="w-10 h-10 rounded-md object-cover"
                        alt="Product"
                    >
                    <div>
                        <p class="text-sm font-medium text-gray-900">
                            {{ $name }}@if(count($items) > 1) <span class="text-xs text-gray-500">(+{{ count($items) - 1 }} more)</span>@endif
                        </p>
                        <p class="text-xs text-gray-500">
                            {{ $timeText }}
                        </p>
                    </div>
                </div>

                <div class="text-right">
                    <p class="text-sm font-medium text-gray-900">
                        ${{ number_format($amount, 2) }}
                    </p>
                    @if($status)
                        <span class="text-xs font-semibold {{ $statusClass }}">
                            {{ ucfirst($status) }}
                        </span>
                    @endif
                </div>
            </li>
        @empty
            <p class="text-sm text-gray-500">No recent orders.</p>
        @endforelse
    </ul>
    
</div>
