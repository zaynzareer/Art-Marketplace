<div class="p-6 space-y-6">

    {{-- Metrics --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
        <x-seller.metric-card
            title="Total Revenue"
            :value="'$' . number_format($metrics['revenue'] ?? 0)"
        />

        <x-seller.metric-card
            title="Total Orders"
            :value="$metrics['orders'] ?? 0"
        />

        <x-seller.metric-card
            title="Products"
            :value="$metrics['products'] ?? 0"
        />
    </div>
    
    {{-- Recent Orders Only --}}
    <x-seller.recent-orders :orders="$recentOrders" />

    {{-- Orders + Chart --}}
    {{-- <div class="grid grid-cols-1 lg:grid-cols-3 gap-6"> --}}
        
        {{-- Chart --}}
        {{-- <div class="bg-white rounded-xl shadow p-6 lg:col-span-1">
            <h2 class="text-lg font-semibold text-gray-900 mb-4">
                Revenue Overview
            </h2>

            <livewire:seller.revenue-chart :data="$chartData" />
        </div> --}}

        {{-- Recent Orders --}}
        {{-- <div class="lg:col-span-2">
            <x-seller.recent-orders :orders="$recentOrders" />
        </div>

    </div> --}}
</div>
