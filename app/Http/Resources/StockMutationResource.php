<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class StockMutationResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
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
            'action' => $this->action,
            'stock_before' => (int) $this->stock_before,
            'stock_after' => (int) $this->stock_after,
            'delta' => (int) $this->delta,
            'user_id' => $this->user_id,
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
            'stock_transaction_id' => $this->stock_transaction_id,
            'transaction' => $this->whenLoaded('transaction', function () {
                if (!$this->transaction) {
                    return null;
                }

                return [
                    'id' => $this->transaction->id,
                    'transaction_number' => $this->transaction->transaction_number,
                    'type' => $this->transaction->type,
                    'transaction_date' => optional($this->transaction->transaction_date)->format('Y-m-d'),
                ];
            }),
            'note' => $this->note,
            'created_at' => optional($this->created_at)->toISOString(),
            'updated_at' => optional($this->updated_at)->toISOString(),
        ];
    }
}
