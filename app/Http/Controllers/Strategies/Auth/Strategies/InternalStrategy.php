<?php

namespace App\Http\Controllers\Strategies\Auth\Strategies;

use App\Http\Controllers\Strategies\Auth\AuthStrategy;
use App\Models\PersonasAdmin;
use App\Models\User;
use Illuminate\Http\Request;

class InternalStrategy implements AuthStrategy
{
    public function authenticate(Request $request): User
    {
        $id = $request->input('_id');

        // Por Email
        if (filter_var($id, FILTER_VALIDATE_EMAIL)) {
            $user = User::where('email', $id)->first();
            if (! $user) {
                throw new \Exception('No se encontró un usuario con ese email.');
            }

            return $user;
        }

        // Por Documento
        $person = PersonasAdmin::where('documento', $id)->first();
        if (! $person) {
            throw new \Exception('No se encontró ninguna persona con ese documento.');
        }

        $userAdmin = $person->UsuarioAdmin;

        if (! $userAdmin || ! $userAdmin->users) {
            throw new \Exception('Usuario no vinculado a la persona');
        }

        return $userAdmin->users;
    }
}
