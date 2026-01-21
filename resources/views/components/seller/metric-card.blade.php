@props(['title', 'value', 'icon' => null, 'iconColor' => 'blue'])

<div class="bg-white rounded-xl shadow p-6 hover:shadow-lg transition-shadow duration-200">
    <div class="flex items-center justify-between">
        <div class="flex-1">
            <p class="text-sm text-gray-500 font-medium">{{ $title }}</p>
            <p class="mt-2 text-3xl font-bold text-gray-900">
                {{ $value }}
            </p>
        </div>
        @if($icon)
            <div class="ml-4 flex-shrink-0">
                <div class="w-12 h-12 bg-{{ $iconColor }}-100 rounded-lg flex items-center justify-center">
                    {!! $icon !!}
                </div>
            </div>
        @endif
    </div>
</div>
