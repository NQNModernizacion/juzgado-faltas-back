<?php

namespace App\Http\Requests\Traits;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

trait TraitRequest
{
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

?>
