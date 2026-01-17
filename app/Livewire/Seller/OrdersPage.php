<?php

namespace App\Livewire\Seller;

use Carbon\Carbon;
use Livewire\Component;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;

class OrdersPage extends Component
{
    public $ordersByDate = [];

    public function fetchOrders()
    {
        $orders = Http::withToken(Session::get('api_token'))
            ->get(route('api.orders.sellerIndex'))
            ->json('data');

        $this->ordersByDate = collect($orders)->groupBy(function ($order) {
            $date = Carbon::parse($order['order_date']);
        
            if ($date->isToday()) return 'Today';
            if ($date->isYesterday()) return 'Yesterday';

            return 'Earlier';
        })->toArray();
    }

    public function updateStatus($orderId, $status)
    {
        $response = Http::withToken(Session::get('api_token'))
            ->post(route('api.orders.update', ['orderId' => $orderId]), [
                'status' => $status,
            ]);

        if ($response->successful()) {
            $this->dispatch('notify', message: 'Order status updated successfully', type: 'success');
            $this->fetchOrders();
        } else {
            $message = $response->json('message') ?? 'Failed to update order status';
            $this->dispatch('notify', message: $message, type: 'error');
        }
    }

    public function mount()
    {
        $this->fetchOrders();
    }

    public function render()
    {
        return view('livewire.seller.orders-page');
    }
}
