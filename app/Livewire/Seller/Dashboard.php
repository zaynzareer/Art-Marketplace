<?php

namespace App\Livewire\Seller;

use Livewire\Component;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;

class Dashboard extends Component
{
    public $metrics = [];
    public $recentOrders = [];
    public $chartData = [];

    public function fetchDashboardData()
    {
        $response = Http::withToken(Session::get('api_token'))->get(route('api.seller.dashboard'));

        if ($response->successful()) {
            $data = $response->json('data');
            
            $this->metrics = [
                'revenue' => $data['revenue'],
                'orders' => $data['total_orders'],
                'products' => $data['product_count'],
            ];
            
            $this->recentOrders = $data['recent_orders'];
            $this->chartData = $data['chart_data'] ?? [];
        } else {
            $this->dispatch('notify', message: 'Failed to load dashboard data', type: 'error');
        }
    }

    public function mount()
    {
        $this->fetchDashboardData();
    }

    public function render()
    {
        return view('livewire.seller.dashboard');
    }
}
