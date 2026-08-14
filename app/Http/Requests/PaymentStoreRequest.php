<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PaymentStoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'invoice_id' => 'required|uuid|exists:invoices,id',
            'payment_date' => 'required|date|before_or_equal:today',
            'amount' => 'required|numeric|min:0.01',
            'payment_method' => 'required|in:' . implode(',', [\App\Models\Payment::METHOD_CASH, \App\Models\Payment::METHOD_TRANSFER, \App\Models\Payment::METHOD_GIRO, \App\Models\Payment::METHOD_LAINNYA]),
            'installment_number' => 'nullable|integer|min:1',
            'notes' => 'nullable|string',
            'payment_proof' => 'nullable|file|mimes:jpg,jpeg,png,webp,pdf|max:2048',
        ];
    }
}
