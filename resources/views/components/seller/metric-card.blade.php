@props(['title', 'value'])

<div class="bg-white rounded-xl shadow p-6">
    <p class="text-sm text-gray-500">{{ $title }}</p>
    <p class="mt-2 text-2xl font-semibold text-gray-900">
        {{ $value }}
    </p>
</div>
