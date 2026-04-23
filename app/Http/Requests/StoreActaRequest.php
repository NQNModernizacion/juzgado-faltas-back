<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreActaRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return false;
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'desestimada' => $this->has('desestimada')
                ? filter_var($this->desestimada, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE)
                : null,
        ]);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'grupo_acta_id' => ['nullable', 'integer', 'exists:grupos_actas,id'],

            'year' => ['nullable', 'string', 'max:10'],

            'oficina_id' => ['nullable', 'exists:oficinas,id'],

            // ASUMIDO: este campo existe en tu tabla/modelo/request
            'numero_acta' => [
                'required',
                'string',
                'max:50',
                Rule::unique('actas', 'numero_acta')->where(function ($query) {
                    return $query->where('oficina_id', $this->oficina_id);
                }),
            ],

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

            'inspector_1_id' => ['nullable', 'exists:inspectores,id'],
            'inspector_2_id' => ['nullable', 'exists:inspectores,id'],

            'infracciones' => ['nullable', 'string'],
        ];
    }
    public function messages(): array
    {
        return [
            'grupo_acta_id.exists' => 'El grupo de acta seleccionado no existe.',

            'year.string' => 'El año debe ser un texto válido.',
            'year.max' => 'El año no puede superar los 10 caracteres.',

            'oficina_id.exists' => 'La oficina seleccionada no existe.',

            'numero_acta.required' => 'El número de acta es obligatorio.',
            'numero_acta.string' => 'El número de acta debe ser un texto válido.',
            'numero_acta.max' => 'El número de acta no puede superar los 50 caracteres.',
            'numero_acta.unique' => 'Ya existe un acta con ese número para la oficina seleccionada.',

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

            'inspector_1_id.exists' => 'El inspector 1 seleccionado no existe.',
            'inspector_2_id.exists' => 'El inspector 2 seleccionado no existe.',

            'infracciones.string' => 'El campo infracciones debe ser un texto válido.',
        ];
    }

    public function attributes(): array
    {
        return [
            'grupo_acta_id' => 'grupo de acta',
            'year' => 'año',
            'oficina_id' => 'oficina',
            'numero_acta' => 'número de acta',
            'fecha_labrada' => 'fecha labrada',
            'fecha_carga' => 'fecha de carga',
            'tipo_id' => 'tipo',
            'sub_tipo_id' => 'sub tipo',
            'ley_id' => 'ley',
            'lugar' => 'lugar',
            'calle_id' => 'calle',
            'numero_calle' => 'número de calle',
            'cruce_id' => 'cruce',
            'estado_acta_id' => 'estado del acta',
            'fecha_estado' => 'fecha de estado',
            'desestimada' => 'desestimada',
            'fecha_notificado' => 'fecha de notificación',
            'inspector_1_id' => 'inspector 1',
            'inspector_2_id' => 'inspector 2',
            'infracciones' => 'infracciones',
        ];
    }
}
