<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AuthRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'type' => ['required', 'in:internal,app_login,refresh_token,enter_app'],

            '_id' => ['required_if:type,internal,enter_app'],

            'password' => ['required_if:type,internal'],

            'device_name' => ['nullable', 'string', 'max:255'],
        ];
    }

    public function messages(): array
    {
        return [
            'type.required' => 'El tipo de autenticación es obligatorio.',
            'type.in' => 'El tipo de autenticación no es válido.',
            '_id.required_if' => 'El identificador (_id) es obligatorio para este tipo de acceso.',
            'password.required_if' => 'La contraseña es obligatoria para el login interno.',
        ];
    }
}
