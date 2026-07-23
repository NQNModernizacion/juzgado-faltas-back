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
        return [
            'acta_id' => ['required', 'exists:actas,id'],
            'observacion' => ['required', 'string'],
            'tipo_archivo' => ['boolean'],
            'archivo' => ['nullable', 'file', 'max:20480', 'mimes:jpg,png,gif,webp,svg,pdf,mp4,mpeg,mov,avi,quicktime,mp3,wav,ogg,m4a,aac,txt,text'],
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge(['tipo_archivo' => $this->hasFile('archivo')]);
    }

    public function messages(): array
    {
        return [
            'acta_id.required' => 'El acta es obligatoria.',
            'acta_id.exists' => 'El acta seleccionada no existe en el sistema.',
            'observacion.required' => 'La observación es obligatoria y debe detallar la prueba.',
            'observacion.string' => 'La observación debe ser una cadena de caracteres.',
            'archivo.file' => 'El archivo subido no es un archivo válido.',
            'archivo.max' => 'El tamaño del archivo no puede superar los 20 megabytes.',
            'archivo.mimes' => 'El formato del archivo no es válido.',
        ];
    }
}
