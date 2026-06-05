<?php

namespace App\Http\Requests;

use App\Http\Requests\Traits\TraitRequest;
use Illuminate\Foundation\Http\FormRequest;

class DesagruparActasRequest extends FormRequest
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
            'actas' => ['required', 'array', 'min:1'],
            'actas.*' => ['integer', 'distinct', 'exists:actas,id'],
        ];
    }

    public function messages(): array
    {
        return [
            'actas.required' => 'Debés enviar al menos una acta para desagrupar.',
            'actas.*.exists' => 'Una de las actas seleccionadas no existe.',
        ];
    }
}
