<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ItemStoreRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'auto_sku' => ['nullable', 'boolean'],
            'sku' => ['required_without:auto_sku', 'nullable', 'string', 'max:255', 'unique:items,sku'],
            'category_id' => ['required', 'uuid', 'exists:categories,id'],
            'unit' => ['required', 'string', 'max:50'],
            'description' => ['nullable', 'string'],
            'image' => ['required', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ];
    }
}
