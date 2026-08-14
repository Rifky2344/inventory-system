<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CustomerUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'address' => 'required|string',
            'province' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'contact' => 'required|string|max:20',
            'email' => 'nullable|email|max:255',
            'pic' => 'nullable|string|max:255',
            'note' => 'nullable|string',
        ];
    }
}
