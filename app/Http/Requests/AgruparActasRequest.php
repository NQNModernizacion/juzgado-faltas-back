<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class AgruparActasRequest extends FormRequest
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
            'acta_ids' => ['required', 'array', 'min:2'],
            'acta_ids.*' => ['integer', 'distinct', 'exists:actas,id'],
        ];
    }

    public function messages(): array
    {
        return [
            'acta_ids.min' => 'Debés enviar al menos 2 actas para agrupar.',
            'acta_ids.*.distinct' => 'No podés repetir actas en la agrupación.',
            'acta_ids.*.exists' => 'El acta :input seleccionada en :attribute no existe.',
        ];
    }

    public function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(sendResponse(null, $validator->errors(), 422));
    }
    protected function failedAuthorization()
    {
        throw new HttpResponseException(
            sendResponse(null, 'No autorizado', 403)
        );
    }
}
