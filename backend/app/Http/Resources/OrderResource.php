<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'user_id' => $this->user_id,
            'full_name' => $this->full_name,
            'email' => $this->email,
            'phone' => $this->phone,
            'delivery_method' => $this->delivery_method,
            'country' => $this->country,
            'city' => $this->city,
            'postal_code' => $this->postal_code,
            'street_name' => $this->street_name,
            'house_number' => $this->house_number,
            'note' => $this->note,
            'payment_method' => $this->payment_method,
            'payment_fee' => $this->payment_fee,
            'products_total' => $this->products_total,
            'total_price' => $this->total_price,
            'status' => $this->status,
            'items' => OrderItemResource::collection($this->whenLoaded('items')),
            'created_at' => $this->created_at,
        ];
    }
}
