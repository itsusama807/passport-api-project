<?php

namespace App\Http\Requests\Api\V1\Permission;

use App\Http\Requests\Api\V1\BaseSeprateValidtion;

class StorePermissionRequest extends BaseSeprateValidtion
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
            'name' => ['required', 'string', 'max:255', 'exists:permissions,name']
        ];
    }
}
