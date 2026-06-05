<?php

namespace App\Http\Requests;

use App\Http\Requests\Traits\TraitRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rule;

class ConsultarPadronRequest extends FormRequest
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
                Rule::exists('estados_generales', 'value')->where(function ($query) {
                    return $query->where('label', 'TIPO_PADRON');
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

            if ($tipo === 'AUT') {
                // Formato viejo: 3 letras y 3 números (AAA123)
                // Formato Mercosur: 2 letras, 3 números y 2 letras (AA123BB)
                $regexAuto = '/^(?:[A-Za-z]{3}\d{3}|[A-Za-z]{2}\d{3}[A-Za-z]{2})$/i';
                if ($identificacion && !preg_match($regexAuto, $identificacion)) {
                    $validator->errors()->add('identificacion', 'La patente no tiene un formato de automóvil de Argentina válido (ej: AAA123 o AA123BB).');
                }
            } elseif ($tipo === 'MOT') {
                // Formato viejo: 3 números y 3 letras (123AAA)
                // Formato Mercosur: 1 letra, 3 números y 3 letras (A123BCD)
                $regexMoto = '/^(?:\d{3}[A-Za-z]{3}|[A-Za-z]\d{3}[A-Za-z]{3})$/i';
                if ($identificacion && !preg_match($regexMoto, $identificacion)) {
                    $validator->errors()->add('identificacion', 'La patente no tiene un formato de motovehículo de Argentina válido (ej: 123AAA o A123BCD).');
                }
            }
        });
    }
}