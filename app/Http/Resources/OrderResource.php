<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'   => $this->id,
            'buyer_id' => $this->buyer_id,
            'seller_id' => $this->seller_id,
            'buyer_name' => $this->whenLoaded('buyer', fn() => $this->buyer->name ?? 'Unknown'),
            'seller_name' => $this->whenLoaded('seller', fn() => $this->seller->name ?? 'Unknown'),
            'order_date' => $this->created_at,
            'status' => $this->status,
            'order_items' => OrderItemResource::collection($this->whenLoaded('orderItems', $this->orderItems)),
            'total' => $this->orderItems->sum(function ($item) {
                return $item->quantity * $item->unit_price;
            })
        ];
    }
}
