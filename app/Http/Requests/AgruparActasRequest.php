<?php

namespace App\Http\Requests;

use App\Http\Requests\Traits\TraitRequest;
use Illuminate\Foundation\Http\FormRequest;

class AgruparActasRequest extends FormRequest
{
    use TraitRequest;
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
            'actas' => ['required', 'array', 'min:2'],
            'actas.*' => ['integer', 'distinct', 'exists:actas,id'],
        ];
    }

    public function messages(): array
    {
        return [
            'actas.min' => 'Debés enviar al menos 2 actas para agrupar.',
            'actas.*.distinct' => 'No podés repetir actas en la agrupación.',
            'actas.*.exists' => 'El acta :input seleccionada en :attribute no existe.',
        ];
    }
}
