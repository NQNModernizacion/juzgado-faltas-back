<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class UpdateSecretariaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            //
        ];
    }

    protected function failedValidation(Validator $validator): void
    {
        throw new HttpResponseException(
            sendResponse(null, $validator->errors(), 422)
        );
    }

    protected function failedAuthorization(): void
    {
        throw new HttpResponseException(
            sendResponse(null, 'No autorizado', 403)
        );
    }
}