<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class StockTransactionResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'transaction_number' => $this->transaction_number,
            'type' => $this->type,
            'transaction_date' => optional($this->transaction_date)->format('Y-m-d'),
            'item_id' => $this->item_id,
            'item_sku_snapshot' => $this->item_sku_snapshot,
            'item_name_snapshot' => $this->item_name_snapshot,
            'item_unit_snapshot' => $this->item_unit_snapshot,
            'item' => $this->whenLoaded('item', function () {
                if (!$this->item) {
                    return null;
                }

                return [
                    'id' => $this->item->id,
                    'sku' => $this->item->sku,
                    'name' => $this->item->name,
                    'category' => $this->item->relationLoaded('category') && $this->item->category
                        ? ['id' => $this->item->category->id, 'name' => $this->item->category->name]
                        : null,
                ];
            }),
            'quantity' => (int) $this->quantity,
            'note' => $this->note,
            'proof_url' => $this->proof_path ? asset('storage/'.$this->proof_path) : null,
            'created_by' => $this->created_by,
            'user' => $this->whenLoaded('user', function () {
                if (!$this->user) {
                    return null;
                }

                return [
                    'id' => $this->user->id,
                    'name' => $this->user->name,
                    'email' => $this->user->email,
                ];
            }),
            'created_at' => optional($this->created_at)->toISOString(),
            'updated_at' => optional($this->updated_at)->toISOString(),
        ];
    }
}
