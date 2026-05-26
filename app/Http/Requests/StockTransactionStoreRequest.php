<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StockTransactionStoreRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'item_id' => ['required', 'uuid', 'exists:items,id'],
            'quantity' => ['required', 'integer', 'min:1'],
            'transaction_date' => ['required', 'date'],
            'note' => ['nullable', 'string'],
            'proof' => ['nullable', 'file', 'max:4096'],
        ];
    }
}
