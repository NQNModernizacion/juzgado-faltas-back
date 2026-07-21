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

        $tipo = $this->has('tipo_archivo') ? $this->boolean('tipo_archivo') : (bool) ($prueba->tipo_archivo ?? false);
        $removerArchivo = filter_var($this->input('remover_archivo'), FILTER_VALIDATE_BOOLEAN);

        $rules = [
            'tipo_archivo' => ['nullable', 'boolean'],
            'observacion' => ['nullable', 'string'],
            'remover_archivo' => ['nullable', 'boolean'],
        ];

        // Si la prueba requiere archivo (tipo_archivo es true)
        if ($tipo) {
            $tieneArchivoActual = $prueba && $prueba->archivo !== null;
            
            // El archivo es obligatorio si:
            // 1. La prueba actualmente no tiene un archivo (ej: era tipo 'texto' y ahora requiere archivo).
            // 2. O el usuario pide remover el archivo actual.
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
            $fileRules[] = 'mimes:jpg,png,gif,webp,svg,pdf,mp4,mpeg,mov,avi,quicktime,mp3,wav,ogg,m4a,aac,txt,text';

            $rules['archivo'] = $fileRules;
        } else {
            // Si no requiere archivo, se prohíbe explícitamente subir archivos
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
            'tipo_archivo.boolean' => 'El tipo de archivo debe indicar si requiere archivo (verdadero) o no (falso).',
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
