<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TransactionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->Transaction_ID,
            'name' => $this->Name,
            'phone' => $this->Phone,
            'address' => $this->Address,
            'status' => $this->Status,
            'totalMoney' => $this->Total_money,
            'paymentMethod' => $this->Payment_Method,
            'note' => $this->Note,
            'createdAt' => $this->Created_at,
            'updatedAt' => $this->Updated_at,
            //'orders' => OrderResource::collection($this->whenLoaded('orders')),
            //'purchaser'=>new UserResource($this->user)
        ];
    }
}
