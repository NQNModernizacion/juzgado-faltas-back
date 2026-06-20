<?php

namespace App\Http\Requests;

use App\Http\Requests\Traits\TraitRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rule;

class ConsultarInfractorRequest extends FormRequest
{
    use TraitRequest;

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'tipo' => [
                'required',
                'string',
                Rule::in(['CUIL', 'CUIT', 'CI', 'LC', 'LE', 'PAS', 'CF', 'DNI', 'OT', 'EXT']),
                Rule::exists('estados_generales', 'value')->where(function ($query) {
                    return $query->where('label', 'DOCUMENTO_TIPO');
                })
            ],
            'identificacion' => [
                'required',
                'string',
            ],
        ];
    }

    /**
     * Configure the validator instance.
     *
     * @param  \Illuminate\Contracts\Validation\Validator  $validator
     * @return void
     */
    public function withValidator(Validator $validator): void
    {
        $validator->after(function ($validator) {
            $tipo = $this->input('tipo');
            $identificacion = $this->input('identificacion');

            if ($tipo === 'DNI') {
                $regexDni = '/^\d{7,8}$/';
                if ($identificacion && !preg_match($regexDni, $identificacion)) {
                    $validator->errors()->add('identificacion', 'El número de DNI no tiene un formato de Argentina válido (debe contener 7 u 8 dígitos).');
                }
            } elseif (in_array($tipo, ['CUIT', 'CUIL'])) {
                $regexCuitCuil = '/^\d{11}$/';
                if ($identificacion && !preg_match($regexCuitCuil, $identificacion)) {
                    $validator->errors()->add('identificacion', "El número de {$tipo} no tiene un formato de Argentina válido (debe contener 11 dígitos numéricos sin guiones).");
                }
            }
        });
    }
}
