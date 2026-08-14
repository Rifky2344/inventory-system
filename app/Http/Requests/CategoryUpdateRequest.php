<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CategoryUpdateRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'category_code' => ['nullable', 'string', 'max:50', \Illuminate\Validation\Rule::unique('categories')->ignore($this->route('category'))],
            'description' => ['nullable', 'string'],
        ];
    }
}
