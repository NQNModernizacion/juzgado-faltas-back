<?php

namespace App\Http\Requests;

use App\Http\Requests\Traits\TraitRequest;
use Illuminate\Foundation\Http\FormRequest;

class UpdatePruebaRequest extends FormRequest
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
        $pruebaId = $this->route('prueba');
        $prueba = $pruebaId ? \App\Models\Prueba::with('archivo')->find($pruebaId) : null;

        $tipo = $this->input('tipo_archivo') ?? ($prueba->tipo_archivo ?? null);
        $removerArchivo = filter_var($this->input('remover_archivo'), FILTER_VALIDATE_BOOLEAN);

        $rules = [
            'tipo_archivo' => ['nullable', 'in:imagen,pdf,video,audio,txt,texto'],
            'observacion' => ['nullable', 'string'],
            'remover_archivo' => ['nullable', 'boolean'],
        ];

        // Si el tipo final (nuevo o existente) requiere archivo (no es 'texto')
        if ($tipo && $tipo !== 'texto') {
            $tieneArchivoActual = $prueba && $prueba->archivo !== null;
            
            // El archivo es obligatorio si:
            // 1. La prueba actualmente no tiene un archivo (ej: era tipo 'texto' y ahora pasa a ser 'pdf').
            // 2. O el usuario pide remover el archivo actual de una prueba que sigue requiriendo archivo (pdf, imagen, etc.).
            // 3. O si el usuario está subiendo un archivo nuevo explícitamente en la petición.
            $debeSubirArchivo = !$tieneArchivoActual || $removerArchivo || $this->hasFile('archivo');

            $fileRules = [];
            if ($debeSubirArchivo) {
                $fileRules[] = 'required';
            } else {
                $fileRules[] = 'nullable';
            }
            
            $fileRules[] = 'file';
            $fileRules[] = 'max:20480';

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
        } elseif ($tipo === 'texto') {
            // Si el tipo es texto, se prohíbe explícitamente subir archivos
            $rules['archivo'] = ['nullable', 'prohibited'];
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
            'tipo_archivo.in' => 'El tipo de archivo seleccionado no es válido.',
            'observacion.string' => 'La observación debe ser una cadena de caracteres.',
            'remover_archivo.boolean' => 'La opción de remover archivo debe ser un valor booleano.',
            'archivo.required' => 'El archivo es obligatorio para el tipo de prueba seleccionado.',
            'archivo.file' => 'El archivo subido no es un archivo válido.',
            'archivo.max' => 'El tamaño del archivo no puede superar los 20 megabytes.',
            'archivo.mimes' => 'El formato del archivo no coincide con el tipo seleccionado.',
            'archivo.prohibited' => 'No se permite adjuntar un archivo cuando el tipo de prueba es texto.',
        ];
    }
}
