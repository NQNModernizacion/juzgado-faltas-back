<?php

namespace App\Http\Requests;

use App\Http\Requests\Traits\TraitRequest;
use Illuminate\Foundation\Http\FormRequest;

class StorePlantillaDocumentoRequest extends FormRequest
{
    use TraitRequest;
    
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'nombre' => 'required|string|max:255',
            'contenido_base_html' => 'required|string',
        ];
    }
}
