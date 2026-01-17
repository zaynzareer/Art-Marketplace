<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CartItemResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $price = (float) ($this->product->price ?? 0);

        return [
            'product_id' => $this->product->id,
            'name'       => $this->product->name,
            'image'      => $this->product->image ?? null,
            'category'   => $this->product->category ?? null,
            'price'      => $price,
            'quantity'   => (int) $this->quantity,
            'line_total' => (float) ($this->quantity * $price),
        ];
    }
}
