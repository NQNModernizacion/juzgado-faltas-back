<?php

namespace App\Http\Requests;

use App\Http\Requests\Traits\TraitRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class UpdateMovimientoRequest extends FormRequest
{
    use TraitRequest;
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
}