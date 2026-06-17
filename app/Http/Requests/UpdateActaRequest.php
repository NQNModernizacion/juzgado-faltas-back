<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;

class UpdateActaRequest extends StoreActaRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        // Obtenemos las reglas de StoreActaRequest
        $rules = parent::rules();

        // El parámetro en la ruta es {id}
        $actaId = $this->route('id');

        // Modificamos solo la regla de numero_acta para ignorar el ID actual
        $rules['numero_acta'] = [
            'required',
            'string',
            'max:50',
            Rule::unique('actas', 'numero_acta')->ignore($actaId)->where(function ($query) {
                return $query
                    ->where('oficina_id', $this->oficina_id)
                    ->whereNull('deleted_at');
            }),
        ];

        return $rules;
    }
}
