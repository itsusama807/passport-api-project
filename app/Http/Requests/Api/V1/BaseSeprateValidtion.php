<?php

namespace App\Http\Requests\Api\V1;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\JsonResponse;

abstract class BaseSeprateValidtion extends FormRequest
{
    public function failedValidation(Validator $validator): JsonResponse
    {
        $errorMessages = implode('|', $validator->errors()->all());
        return throw new HttpResponseException(
            response()->json([
                'status' => false,
                'message' => $errorMessages,
            ], JsonResponse::HTTP_UNPROCESSABLE_ENTITY)
        );
    }

}
