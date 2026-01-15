<div>
    <!-- Toast Container -->
    <div
        class="fixed top-4 right-4 z-50 space-y-3"
        style="max-width: 24rem; width: 100%; padding-right: 1rem;"
    >
        @foreach($notifications as $notification)
            <div
                x-data="{
                    show: false,
                    init() {
                        this.$nextTick(() => {
                            this.show = true;
                            setTimeout(() => {
                                this.show = false;
                                setTimeout(() => {
                                    $wire.removeNotification({{ $notification['id'] }});
                                }, 300);
                            }, 4000);
                        });
                    }
                }"
                x-show="show"
                x-transition:enter="transform transition ease-out duration-300"
                x-transition:enter-start="translate-x-full opacity-0"
                x-transition:enter-end="translate-x-0 opacity-100"
                x-transition:leave="transform transition ease-in duration-300"
                x-transition:leave-start="translate-x-0 opacity-100"
                x-transition:leave-end="translate-x-full opacity-0"
                @class([
                    'flex items-start p-4 rounded-lg shadow-lg border backdrop-blur-sm',
                    'bg-green-50 border-green-200' => $notification['type'] === 'success',
                    'bg-red-50 border-red-200' => $notification['type'] === 'error',
                    'bg-yellow-50 border-yellow-200' => $notification['type'] === 'warning',
                    'bg-blue-50 border-blue-200' => $notification['type'] === 'info',
                ])
                wire:key="notification-{{ $notification['id'] }}"
            >
                <!-- Icon -->
                <div class="flex-shrink-0">
                    @if($notification['type'] === 'success')
                        <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    @elseif($notification['type'] === 'error')
                        <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z" />
                        </svg>
                    @elseif($notification['type'] === 'warning')
                        <svg class="w-5 h-5 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                    @else
                        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    @endif
                </div>

                <!-- Message -->
                <div class="ml-3 flex-1">
                    <p @class([
                        'text-sm font-medium',
                        'text-green-800' => $notification['type'] === 'success',
                        'text-red-800' => $notification['type'] === 'error',
                        'text-yellow-800' => $notification['type'] === 'warning',
                        'text-blue-800' => $notification['type'] === 'info',
                    ])>
                        {{ $notification['message'] }}
                    </p>
                </div>

                <!-- Close Button -->
                <button
                    type="button"
                    @click="show = false; setTimeout(() => $wire.removeNotification({{ $notification['id'] }}), 300)"
                    @class([
                        'ml-3 flex-shrink-0 rounded-md p-1.5 inline-flex focus:outline-none focus:ring-2 focus:ring-offset-2',
                        'text-green-600 hover:bg-green-100 focus:ring-green-500' => $notification['type'] === 'success',
                        'text-red-600 hover:bg-red-100 focus:ring-red-500' => $notification['type'] === 'error',
                        'text-yellow-600 hover:bg-yellow-100 focus:ring-yellow-500' => $notification['type'] === 'warning',
                        'text-blue-600 hover:bg-blue-100 focus:ring-blue-500' => $notification['type'] === 'info',
                    ])
                >
                    <span class="sr-only">Close</span>
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        @endforeach
    </div>
</div>

