<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DashboardResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'revenue' => $this->resource['revenue'],
            'total_orders' => $this->resource['total_orders'],
            'product_count' => $this->resource['product_count'],
            'recent_orders' => OrderResource::collection($this->resource['recent_orders']),
            'chart_data' => $this->resource['chart_data'] ?? [],
        ];
    }
}
