<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AgruparActasRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return false;
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
        ];
    }
}
