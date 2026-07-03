<?php

namespace App\Http\Requests;

use App\Http\Requests\Traits\TraitRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class StoreEstadoProcesalRequest extends FormRequest
{
    use TraitRequest;
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'estado' => 'required|string|max:10|unique:estados_procesales,estado',
            'descripcion' => 'required|string|max:255',
            'tipo' => 'nullable|integer',
            'es_antec' => 'boolean',
            'antec_vig_dias' => 'nullable|integer',
            'porc_bonif' => 'nullable|integer',
            'bonif_vig_dias' => 'nullable|integer',
            'gen_notif' => 'boolean',
            'notif_cant_dias' => 'nullable|integer',
            'form_autom' => 'nullable|boolean',
            'perm_pago' => 'boolean',
            'perm_plan' => 'boolean',
            'perm_vol' => 'boolean',
            'desestima' => 'boolean',
            'tipo_causa' => 'nullable|string|max:255',
        ];
    }
}