<?php

namespace App\Http\Requests;

use App\Http\Requests\Traits\TraitRequest;
use App\Models\DocumentoLegal;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class StoreDocumentoLegalRequest extends FormRequest
{
    use TraitRequest;
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        if ($this->route('id') && !$this->has('acta_id')) {
            $this->merge([
                'acta_id' => $this->route('id')
            ]);
        } elseif ($this->route('documento') && !$this->has('acta_id')) {
            $doc = DocumentoLegal::find($this->route('documento'));
            if ($doc) {
                $this->merge([
                    'acta_id' => $doc->acta_id
                ]);
            }
        }
    }

    public function rules(): array
    {
        return [
            'plantilla_documento_id' => 'nullable|exists:plantilla_documentos,id',
            'acta_id' => 'required|exists:actas,id',
            'tipo' => 'nullable|string',
            'contenido_html' => 'required|string',
            'metadata' => 'nullable|array'
        ];
    }
}