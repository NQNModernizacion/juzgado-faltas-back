<?php

namespace App\Http\Requests;

use App\Http\Requests\Traits\TraitRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class UpdateMovimientoRequest extends FormRequest
{
    use TraitRequest;
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'acta_id' => 'required|exists:actas,id',
            'oficina_id_destino' => 'required|exists:oficina_internas,id',
            'motivo' => 'nullable|string',
            'fojas' => 'nullable|string',
        ];
    }
}