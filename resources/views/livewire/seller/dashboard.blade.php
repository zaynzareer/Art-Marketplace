<div class="px-4 sm:px-6 py-6 sm:py-8 space-y-6 md:space-y-8">

    {{-- Metrics Cards with Icons --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 md:gap-6">
        <x-seller.metric-card
            title="Total Revenue"
            :value="'$' . number_format($metrics['revenue'] ?? 0, 2)"
            iconColor="green"
        >
            <x-slot name="icon">
                <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </x-slot>
        </x-seller.metric-card>

        <x-seller.metric-card
            title="Total Orders"
            :value="$metrics['orders'] ?? 0"
            iconColor="blue"
        >
            <x-slot name="icon">
                <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                </svg>
            </x-slot>
        </x-seller.metric-card>

        <x-seller.metric-card
            title="Products"
            :value="$metrics['products'] ?? 0"
            iconColor="purple"
        >
            <x-slot name="icon">
                <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                </svg>
            </x-slot>
        </x-seller.metric-card>
    </div>

    {{-- Charts Section --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 md:gap-6">
        {{-- Revenue Chart --}}
        <div class="bg-white rounded-xl shadow p-4 sm:p-6">
            <h2 class="text-base sm:text-lg font-semibold text-gray-900 mb-4 flex items-center gap-2 truncate">
                <svg class="w-5 h-5 flex-shrink-0 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 12l3-3 3 3 4-4M8 21l4-4 4 4M3 4h18M4 4h16v12a1 1 0 01-1 1H5a1 1 0 01-1-1V4z" />
                </svg>
                <span class="truncate">Revenue Trend (This Month)</span>
            </h2>
            <canvas id="revenueChart" class="w-full" style="max-height: 300px;"></canvas>
        </div>

        {{-- Order Status Chart --}}
        <div class="bg-white rounded-xl shadow p-4 sm:p-6">
            <h2 class="text-base sm:text-lg font-semibold text-gray-900 mb-4 flex items-center gap-2 truncate">
                <svg class="w-5 h-5 flex-shrink-0 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                </svg>
                <span class="truncate">Orders by Status</span>
            </h2>
            <canvas id="statusChart" class="w-full" style="max-height: 300px;"></canvas>
        </div>
    </div>

    {{-- Top Products Chart --}}
    <div class="bg-white rounded-xl shadow p-4 sm:p-6">
        <h2 class="text-base sm:text-lg font-semibold text-gray-900 mb-4 flex items-center gap-2 truncate">
            <svg class="w-5 h-5 flex-shrink-0 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
            </svg>
            <span class="truncate">Top Selling Products (Last 30 Days)</span>
        </h2>
        <canvas id="topProductsChart" class="w-full" style="max-height: 300px;"></canvas>
    </div>
    
    {{-- Recent Orders --}}
    <x-seller.recent-orders :orders="$recentOrders" />

    {{-- Chart.js Scripts --}}
    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Revenue Chart
            const revenueData = @json($chartData['revenue_by_month'] ?? []);
            const revenueLabels = Object.keys(revenueData);
            const revenueValues = Object.values(revenueData);

            const revenueCtx = document.getElementById('revenueChart');
            if (revenueCtx) {
                new Chart(revenueCtx, {
                    type: 'line',
                    data: {
                        labels: revenueLabels.map(label => {
                            const date = new Date(label);
                            return date.toLocaleDateString('en-US', { month: 'short', day: 'numeric' });
                        }),
                        datasets: [{
                            label: 'Revenue',
                            data: revenueValues,
                            borderColor: 'rgb(59, 130, 246)',
                            backgroundColor: 'rgba(59, 130, 246, 0.1)',
                            tension: 0.4,
                            fill: true,
                            pointRadius: 5,
                            pointHoverRadius: 7
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: true,
                        plugins: {
                            legend: {
                                display: false
                            },
                            tooltip: {
                                callbacks: {
                                    label: function(context) {
                                        return 'Revenue: $' + context.parsed.y.toFixed(2);
                                    }
                                }
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                ticks: {
                                    callback: function(value) {
                                        return '$' + value;
                                    }
                                }
                            }
                        }
                    }
                });
            }

            // Order Status Chart
            const statusData = @json($chartData['orders_by_status'] ?? []);
            const statusLabels = Object.keys(statusData);
            const statusValues = Object.values(statusData);

            const statusCtx = document.getElementById('statusChart');
            if (statusCtx) {
                // Map status to semantic colors
                const statusColorMap = {
                    'pending': { bg: 'rgba(251, 191, 36, 0.8)', border: 'rgb(251, 191, 36)' },      // yellow - pending
                    'processing': { bg: 'rgba(99, 102, 241, 0.8)', border: 'rgb(99, 102, 241)' },  // indigo - processing
                    'shipped': { bg: 'rgba(59, 130, 246, 0.8)', border: 'rgb(59, 130, 246)' },     // blue - shipped
                    'delivered': { bg: 'rgba(34, 197, 94, 0.8)', border: 'rgb(34, 197, 94)' },     // green - delivered
                    'failed': { bg: 'rgba(239, 68, 68, 0.8)', border: 'rgb(239, 68, 68)' }         // red - failed
                };

                const bgColors = statusLabels.map(label => statusColorMap[label]?.bg || 'rgba(107, 114, 128, 0.8)');
                const borderColors = statusLabels.map(label => statusColorMap[label]?.border || 'rgb(107, 114, 128)');

                new Chart(statusCtx, {
                    type: 'doughnut',
                    data: {
                        labels: statusLabels.map(label => label.charAt(0).toUpperCase() + label.slice(1)),
                        datasets: [{
                            data: statusValues,
                            backgroundColor: bgColors,
                            borderColor: borderColors,
                            borderWidth: 2
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: true,
                        plugins: {
                            legend: {
                                position: 'bottom',
                                labels: {
                                    padding: 15,
                                    usePointStyle: true
                                }
                            }
                        }
                    }
                });
            }

            // Top Products Chart
            const productsData = @json($chartData['top_products'] ?? []);
            const productsLabels = Object.keys(productsData);
            const productsValues = Object.values(productsData);

            const productsCtx = document.getElementById('topProductsChart');
            if (productsCtx) {
                new Chart(productsCtx, {
                    type: 'bar',
                    data: {
                        labels: productsLabels,
                        datasets: [{
                            label: 'Units Sold',
                            data: productsValues,
                            backgroundColor: 'rgba(34, 197, 94, 0.8)',
                            borderColor: 'rgb(34, 197, 94)',
                            borderWidth: 1,
                            borderRadius: 8,
                            barThickness: 20
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: true,
                        indexAxis: 'y',
                        plugins: {
                            legend: {
                                display: false
                            }
                        },
                        scales: {
                            x: {
                                beginAtZero: true,
                                ticks: {
                                    stepSize: 1
                                }
                            }
                        }
                    }
                });
            }
        });
    </script>
    @endpush
</div>
