<?php

namespace App\Http\Requests;

use App\Http\Requests\Traits\TraitRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class RegistrarEstadoProcesalRequest extends FormRequest
{
    use TraitRequest;
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'acta_id' => 'required|integer|exists:actas,id',
            'estado_procesal_id' => 'required|integer|exists:estados_procesales,id',
            'fecha' => 'nullable|date',
            'observacion' => 'nullable|string',
            'infractor_id' => 'nullable|integer|exists:infractores,id',
            'imputado_datos' => 'nullable|string|max:255',
        ];
    }

    public function messages(): array
    {
        return [
            'acta_id.required' => 'El acta es obligatoria.',
            'acta_id.integer' => 'El ID del acta debe ser un número entero.',
            'acta_id.exists' => 'El acta seleccionada no existe.',
            'estado_procesal_id.required' => 'El estado procesal es obligatorio.',
            'estado_procesal_id.integer' => 'El ID del estado procesal debe ser un número entero.',
            'estado_procesal_id.exists' => 'El estado procesal seleccionado no existe.',
            'fecha.date' => 'La fecha ingresada no tiene un formato válido.',
            'observacion.string' => 'La observación debe ser una cadena de texto.',
            'infractor_id.integer' => 'El ID del infractor debe ser un número entero.',
            'infractor_id.exists' => 'El infractor seleccionado no existe.',
            'imputado_datos.string' => 'Los datos del imputado deben ser una cadena de texto.',
            'imputado_datos.max' => 'Los datos del imputado no pueden superar los 255 caracteres.',
        ];
    }
}