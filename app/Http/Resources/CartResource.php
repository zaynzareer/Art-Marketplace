<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CartResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $items = CartItemResource::collection($this->cartItems);

        return [
            'cart_id'    => $this->id,
            'cart_items' => $items, // backward compatibility
            'total_items' => (int) $this->cartItems->sum('quantity'),
            'subtotal'   => (float) ($this->cartItems->sum(function ($item) {
                $price = $item->product->price ?? 0;
                return $item->quantity * $price;
            })),
        ];
    }
}
