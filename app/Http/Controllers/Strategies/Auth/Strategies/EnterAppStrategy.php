<?php

namespace App\Http\Controllers\Strategies\Auth\Strategies;

use App\Http\Controllers\Strategies\Auth\AuthStrategy;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;

class EnterAppStrategy implements AuthStrategy
{
    public function authenticate(Request $request): User
    {
        $userId = $request->input('_id');

        if (! $userId) {
            throw new Exception('ID de usuario no proporcionado para Enter App');
        }

        $user = User::find($userId);

        if (! $user) {
            throw new Exception("No se encontró ningún usuario con el ID: {$userId}");
        }

        return $user;
    }
}
