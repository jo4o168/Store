<?php

namespace App\Http\Resources\Orders;

use App\Enum\OrderStatus;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderDetailResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'order_number' => $this->order_number,
            'status' => strtolower(OrderStatus::tryFrom((int) $this->status)?->name ?? 'PENDING'),
            'total_amount' => (float) $this->total_amount,
            'delivery_address' => $this->delivery_address,
            'notes' => $this->notes,
            'producer_message' => $this->producer_message,
            'producer_id' => (int) $this->producer_id,
            'customer_id' => (int) $this->customer_id,
            'customer' => $this->customer ? [
                'id' => $this->customer->id,
                'name' => $this->customer->name,
                'email' => $this->customer->email,
            ] : null,
            'items' => $this->items->map(fn ($item) => [
                'id' => $item->id,
                'product_name' => $item->product_name,
                'quantity' => (int) $item->quantity,
                'unit_price' => (float) $item->unit_price,
                'product_id' => $item->product_id,
            ])->values(),
            'created_at' => $this->created_at,
        ];
    }
}
