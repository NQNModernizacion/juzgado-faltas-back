<?php

namespace App\Http\Requests;

use App\Http\Requests\Traits\TraitRequest;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;

class AuthRequest extends FormRequest
{
    use TraitRequest;
    public static $rules = [];
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * El campo _id puede ser un email o un DNI
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(Request $request): array
    {
        if ($request->type == null || !in_array($request->type, ['internal', 'app_login', 'refresh_token', 'enter_app'])) {
            return ['type' => ['required', 'in:internal,app_login,refresh_token,enter_app']];
        } else {
            $ret = [];
            switch ($request->type) {
                case 'internal':
                case 'enter_app':
                    $ret = [
                        '_id' => 'required',
                        'password' => 'required',
                    ];
                    break;
                case 'app_login':
                case 'refresh_token':
                    break;
                default:
                    $ret = ['type' => ['required', 'in:internal,app_login,refresh_token,enter_app']];
                    break;
            }
            return $ret;
        }
    }

    public function messages(): array
    {
        return [
            '_id.required' => 'El campo _id es obligatorio',
            'type.required' => 'El campo type es requerido',
            'type.in' => 'El campo type debe ser "internal", "app_login", "refresh_token" o "enter_app"',
            'password.required' => 'El campo contraseña es requerido',
        ];
    }
}
