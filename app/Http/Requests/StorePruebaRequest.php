<?php

namespace App\Http\Requests;

use App\Http\Requests\Traits\TraitRequest;
use Illuminate\Foundation\Http\FormRequest;

class StorePruebaRequest extends FormRequest
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
        $tipo = $this->input('tipo_archivo');

        $rules = [
            'acta_id' => ['required', 'exists:actas,id'],
            'tipo_archivo' => ['required', 'in:imagen,pdf,video,audio,txt,texto'],
            'observacion' => ['required', 'string'],
        ];

        if ($tipo === 'texto') {
            $rules['archivo'] = ['nullable', 'prohibited'];
        } elseif ($tipo) {
            $fileRules = ['required', 'file', 'max:20480'];

            switch ($tipo) {
                case 'imagen':
                    $fileRules[] = 'mimes:jpeg,jpg,png,gif,webp,svg';
                    break;
                case 'pdf':
                    $fileRules[] = 'mimes:pdf';
                    break;
                case 'video':
                    $fileRules[] = 'mimes:mp4,mpeg,mov,avi,quicktime';
                    break;
                case 'audio':
                    $fileRules[] = 'mimes:mp3,wav,ogg,m4a,aac';
                    break;
                case 'txt':
                    $fileRules[] = 'mimes:txt,text';
                    break;
            }

            $rules['archivo'] = $fileRules;
        }

        return $rules;
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array
     */
    public function messages(): array
    {
        return [
            'acta_id.required' => 'El acta es obligatoria.',
            'acta_id.exists' => 'El acta seleccionada no existe en el sistema.',
            'tipo_archivo.required' => 'El tipo de archivo es obligatorio.',
            'tipo_archivo.in' => 'El tipo de archivo seleccionado no es válido.',
            'observacion.required' => 'La observación es obligatoria y debe detallar la prueba.',
            'observacion.string' => 'La observación debe ser una cadena de caracteres.',
            'archivo.required' => 'El archivo es obligatorio para el tipo de prueba seleccionado.',
            'archivo.file' => 'El archivo subido no es un archivo válido.',
            'archivo.max' => 'El tamaño del archivo no puede superar los 20 megabytes.',
            'archivo.mimes' => 'El formato del archivo no coincide con el tipo seleccionado.',
            'archivo.prohibited' => 'No se permite adjuntar un archivo cuando el tipo de prueba es texto.',
        ];
    }
}
