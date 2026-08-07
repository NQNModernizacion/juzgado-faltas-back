<?php

namespace App\Http\Requests;

use App\Http\Requests\Traits\TraitRequest;
use Illuminate\Foundation\Http\FormRequest;

class StorePruebaRequest extends FormRequest
{
    use TraitRequest;

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $tipo = $this->has('tipo_archivo') ? $this->boolean('tipo_archivo') : $this->hasFile('archivo');

        return [
            'acta_id' => ['required', 'exists:actas,id'],
            'observacion' => ['required', 'string'],
            'tipo_archivo' => ['required', 'boolean'],
            'archivo' => $tipo
                ? ['required', 'file', 'max:20480', 'mimes:jpg,png,gif,webp,svg,pdf,mp4,mpeg,mov,avi,quicktime,mp3,wav,ogg,m4a,aac,txt,text']
                : ['nullable', 'prohibited'],
        ];
    }

    protected function prepareForValidation(): void
    {
        if (!$this->has('tipo_archivo')) {
            $this->merge(['tipo_archivo' => $this->hasFile('archivo')]);
        }
    }

    public function messages(): array
    {
        return [
            'acta_id.required' => 'El acta es obligatoria.',
            'acta_id.exists' => 'El acta seleccionada no existe en el sistema.',
            'observacion.required' => 'La observación es obligatoria y debe detallar la prueba.',
            'observacion.string' => 'La observación debe ser una cadena de caracteres.',
            'tipo_archivo.required' => 'El tipo de archivo es obligatorio.',
            'tipo_archivo.boolean' => 'El tipo de archivo debe ser un valor booleano.',
            'archivo.required' => 'El archivo es obligatorio para el tipo de prueba seleccionado.',
            'archivo.file' => 'El archivo subido no es un archivo válido.',
            'archivo.max' => 'El tamaño del archivo no puede superar los 20 megabytes.',
            'archivo.mimes' => 'El formato del archivo no es válido.',
            'archivo.prohibited' => 'No se permite adjuntar un archivo cuando el tipo de prueba es texto.',
        ];
    }
}
