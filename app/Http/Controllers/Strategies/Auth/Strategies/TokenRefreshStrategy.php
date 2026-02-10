<?php

namespace App\Http\Controllers\Strategies\AuthStrategies\Auth\Strategies;

use App\Http\Controllers\Strategies\Auth\AuthStrategy;
use App\Models\User;
use Illuminate\Http\Request;

/* Maneja app_login y refresh token */
class TokenRefreshStrategy implements AuthStrategy
{
    public function authenticate(Request $request): User
    {
        $user = $request->user('sanctum');

        if (! $user || ! $user->currentAccessToken()) {
            throw new \Exception('No se ha proporcionado un token válido');
        }

        // Rotación de token
        $user->currentAccessToken()->delete();

        return $user;
    }
}
