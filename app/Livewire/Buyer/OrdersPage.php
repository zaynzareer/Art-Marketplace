<?php

namespace App\Livewire\Buyer;

use Carbon\Carbon;
use Livewire\Component;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;

class OrdersPage extends Component
{
    public $ordersByDate = [];

    public function fetchOrders()
    {
        $response = Http::withToken(Session::get('api_token'))
            ->get(route('api.orders.index'));

        if ($response->successful()) {
            $orders = $response->json('data');

            $this->ordersByDate = collect($orders)->groupBy(function ($order) {
                $date = Carbon::parse($order['order_date']);
            
                if ($date->isToday()) {
                    return 'Today';
                }
                
                if ($date->isCurrentWeek()) {
                    return 'This Week';
                }
                
                if ($date->isCurrentMonth()) {
                    return 'This Month';
                }

                return $date->format('F Y');
            })->toArray();
        } else {
            $this->dispatch('notify', message: 'Failed to load orders', type: 'error');
        }
    }

    public function mount()
    {
        $this->fetchOrders();
    }
    
    public function render()
    {
        return view('livewire.buyer.orders-page');
    }
}
