<?php

namespace App\Http\Requests;

use App\Traits\Core\Api\ResponseTrait;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\JsonResponse;

class ApiFormRequest extends FormRequest
{
    use ResponseTrait;


    /**
     * Handle failed validation and return structured JSON errors.
     */
    protected function failedValidation(Validator $validator)
    {
        $errors = (new ValidationException($validator))->errors();
        $formattedErrors = [];

        foreach ($errors as $field => $details) {
            $formattedErrors[$field] = [
                'details' => $details,
                'source' => ['pointer' => "/data/attributes/{$field}"],
            ];
        }

        throw new HttpResponseException(
            $this->responseErrors($formattedErrors, 'Validation Error', 422)
        );
    }

    /**
     * Build a standardized JSON error response.
     */
    protected function responseErrors(array $errors, string $title = "Validation Error", int $status = 400, array $headers = []): JsonResponse
    {
        return response()->json([
            'title' => $title,
            'errors' => $errors
        ], $status, $headers);
    }
}
