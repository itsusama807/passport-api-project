<?php

namespace App\Http\Requests\Api\V1\Product;

use App\Http\Requests\Api\V1\BaseSeprateValidtion;

class UpdateProductRequest extends BaseSeprateValidtion
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'category_ids' => ['required', 'array'],
            'category_ids.*' => ['integer', 'exists:categories,id']
        ];
    }
}
