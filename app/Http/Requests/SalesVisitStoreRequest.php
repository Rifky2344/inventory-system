<?php

namespace App\Http\Requests;

use App\Models\SalesVisit;
use Illuminate\Foundation\Http\FormRequest;

class SalesVisitStoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'customer_id' => 'required|uuid|exists:customers,id',
            'visit_date' => 'required|date|before_or_equal:today',
            'visit_result' => 'required|string|max:500',
            'note' => 'nullable|string',
            'follow_up_status' => 'required|in:' . implode(',', ['Pending', 'Completed']),
        ];
    }
}
