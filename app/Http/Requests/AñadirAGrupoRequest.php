<?php

namespace App\Http\Requests;

use App\Http\Requests\Traits\TraitRequest;
use Illuminate\Foundation\Http\FormRequest;

class AñadirAGrupoRequest extends FormRequest
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
            'grupo_id' => ['required', 'integer', 'exists:grupos_actas,id'],
            'actas' => ['required', 'array', 'min:1'],
            'actas.*' => ['integer', 'distinct', 'exists:actas,id'],
        ];
    }

    public function messages(): array
    {
        return [
            'grupo_id.required' => 'El ID del grupo es obligatorio.',
            'grupo_id.exists' => 'El grupo seleccionado no existe.',
            'actas.required' => 'Debés enviar al menos una acta.',
            'actas.*.exists' => 'Una de las actas seleccionadas no existe.',
        ];
    }
}
