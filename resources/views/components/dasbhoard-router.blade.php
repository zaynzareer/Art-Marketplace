<div class="p-6 lg:p-8 bg-white border-b border-gray-200">
    @if(Auth::user()->role === 'buyer')
    <livewire:buyer.dashboard />
    @elseif(Auth::user()->role === 'seller')
    <livewire:seller.dashboard />
    @endif
</div>
