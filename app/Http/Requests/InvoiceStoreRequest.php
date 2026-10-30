<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class InvoiceStoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'type' => 'required|in:out,in',
            'customer_id' => 'required|uuid|exists:customers,id',
            'sales_id' => 'nullable|uuid|exists:users,id',
            'invoice_date' => 'required|date|before_or_equal:today',
            'due_date' => 'required|date|after_or_equal:invoice_date',
            'is_auto_invoice' => 'required|boolean',
            'invoice_number' => 'nullable|required_if:is_auto_invoice,0|string|unique:invoices,invoice_number',
            'note' => 'nullable|string',
            'items' => 'required|array|min:1',
            'items.*.item_id' => 'required|uuid|exists:items,id',
            'items.*.qty' => 'required|integer|min:1',
            'items.*.unit_price' => 'required|numeric|min:0.01',
        ];
    }
}
