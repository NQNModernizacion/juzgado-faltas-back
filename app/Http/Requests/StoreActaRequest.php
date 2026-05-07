<?php

namespace App\Http\Requests;

use App\Models\Inspector;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rule;


class StoreActaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'desestimada' => $this->has('desestimada')
                ? filter_var($this->desestimada, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE)
                : null,
        ]);
    }

    public function rules(): array
    {
        return [
            'grupo_acta_id' => ['nullable', 'integer', 'exists:grupos_actas,id'],
            'numero_acta' => [
                'required',
                'string',
                'max:50',
                Rule::unique('actas', 'numero_acta')->where(function ($query) {
                    return $query
                        ->where('oficina_id', $this->oficina_id)
                        ->whereNull('deleted_at');
                }),
            ],

            'year' => ['nullable', 'string', 'max:10'],
            'oficina_id' => ['nullable', 'exists:oficinas,id'],
            'fecha_labrada' => ['nullable', 'date'],
            'fecha_carga' => ['nullable', 'date'],

            'tipo_id' => ['nullable', 'exists:estados_generales,id'],
            'sub_tipo_id' => ['nullable', 'exists:estados_generales,id'],
            'ley_id' => ['nullable', 'exists:estados_generales,id'],

            'lugar' => ['nullable', 'string', 'max:255'],

            'calle_id' => ['nullable', 'exists:calles,id'],
            'numero_calle' => ['nullable', 'integer', 'min:0'],
            'cruce_id' => ['nullable', 'exists:calles,id'],

            'estado_acta_id' => ['nullable', 'exists:estados_generales,id'],
            'fecha_estado' => ['nullable', 'date'],

            'desestimada' => ['nullable', 'boolean'],
            'fecha_notificado' => ['nullable', 'date'],

            'inspector_1_id' => ['nullable', 'integer', 'exists:inspectores,id'],
            'inspector_2_id' => ['nullable', 'integer', 'exists:inspectores,id'],

            'infracciones' => ['nullable', 'array'],
            'infracciones.*' => ['integer', 'exists:infracciones,id'],

            'padrones' => ['required', 'array'],
            'padrones.*.tipo_id' => ['required', Rule::exists('estados_generales', 'id')->where(function ($query) {
                return $query->where('label', 'TIPO_PADRON');
            })],
            'padrones.*.identificacion' => ['required', 'string'],
            'padrones.*.nombre' => ['required', 'string'],
            'padrones.*.categoria_padron_id' => [
                'nullable',
                Rule::exists('estados_generales', 'id')->where(function ($query) {
                    return $query->where('label', 'CATEGORIA_PADRON');
                })
            ],
            'infractores' => ['required', 'array'],
            'infractores.*.tipo_id' => ['required', Rule::exists('estados_generales', 'id')->where(function ($query) {
                return $query->where('label', 'DOCUMENTO_TIPO');
            })],
            'infractores.*.identificacion' => ['required', 'string'],
            'infractores.*.documento' => ['required', 'string'],
            'infractores.*.nombre' => ['required', 'string'],
            'infractores.*.domicilio' => ['nullable', 'string'],
            'infractores.*.categoria_infractor_id' => ['nullable', 'integer', Rule::exists('estados_generales', 'id')->where(function ($query) {
                return $query->where('label', 'CATEGORIA_INFRACTOR');
            })],
        ];
    }

    public function withValidator($validator): void
    {
        $validator->after(function ($validator) {
            $oficinaId = $this->oficina_id;

            if (!$oficinaId) {
                return;
            }

            if ($this->filled('inspector_1_id') && !$this->inspectorPerteneceAOficina($this->inspector_1_id, $oficinaId)) {
                $validator->errors()->add(
                    'inspector_1_id',
                    'El inspector 1 no pertenece a la oficina seleccionada.'
                );
            }

            if ($this->filled('inspector_2_id') && !$this->inspectorPerteneceAOficina($this->inspector_2_id, $oficinaId)) {
                $validator->errors()->add(
                    'inspector_2_id',
                    'El inspector 2 no pertenece a la oficina seleccionada.'
                );
            }
        });
    }

    protected function inspectorPerteneceAOficina(int $inspectorId, int $oficinaId): bool
    {
        return Inspector::where('id', $inspectorId)
            ->where('oficina_id', $oficinaId)
            ->exists();
    }

    public function messages(): array
    {
        return [
            'grupo_acta_id.integer' => 'El grupo de acta debe ser un número válido.',
            'grupo_acta_id.exists' => 'El grupo de acta seleccionado no existe.',

            'numero_acta.required' => 'El número de acta es obligatorio.',
            'numero_acta.string' => 'El número de acta debe ser un texto válido.',
            'numero_acta.max' => 'El número de acta no puede superar los 50 caracteres.',
            'numero_acta.unique' => 'Ya existe un acta con ese número para la oficina seleccionada.',

            'year.string' => 'El año debe ser un texto válido.',
            'year.max' => 'El año no puede superar los 10 caracteres.',

            'oficina_id.exists' => 'La oficina seleccionada no existe.',

            'fecha_labrada.date' => 'La fecha labrada debe tener una fecha válida.',
            'fecha_carga.date' => 'La fecha de carga debe tener una fecha válida.',

            'tipo_id.exists' => 'El tipo seleccionado no existe.',
            'sub_tipo_id.exists' => 'El sub tipo seleccionado no existe.',
            'ley_id.exists' => 'La ley seleccionada no existe.',

            'lugar.string' => 'El lugar debe ser un texto válido.',
            'lugar.max' => 'El lugar no puede superar los 255 caracteres.',

            'calle_id.exists' => 'La calle seleccionada no existe.',
            'numero_calle.integer' => 'El número de calle debe ser un número entero.',
            'numero_calle.min' => 'El número de calle no puede ser negativo.',
            'cruce_id.exists' => 'El cruce seleccionado no existe.',

            'estado_acta_id.exists' => 'El estado del acta seleccionado no existe.',
            'fecha_estado.date' => 'La fecha de estado debe tener una fecha válida.',

            'desestimada.boolean' => 'El campo desestimada debe ser verdadero o falso.',

            'fecha_notificado.date' => 'La fecha de notificación debe tener una fecha válida.',

            'inspector_1_id.integer' => 'El inspector 1 debe ser un número válido.',
            'inspector_1_id.exists' => 'El inspector 1 seleccionado no existe.',

            'inspector_2_id.integer' => 'El inspector 2 debe ser un número válido.',
            'inspector_2_id.exists' => 'El inspector 2 seleccionado no existe.',

            'infracciones.array' => 'El campo infracciones debe ser un arreglo.',
            'infracciones.*.integer' => 'Cada infracción debe ser un número entero.',
            'infracciones.*.exists' => 'Una de las infracciones seleccionadas no existe.',
        ];
    }

    public function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(sendResponse(null, $validator->errors(), 422));
    }
    protected function failedAuthorization()
    {
        throw new HttpResponseException(
            sendResponse(null, 'No autorizado', 403)
        );
    }
}
