<?php

namespace App\Http\Controllers\Strategies\Auth\Strategies;

use App\Http\Controllers\Strategies\Auth\AuthStrategy;

class AuthStrategyResolver
{
    public static function resolve(string $type): AuthStrategy
    {
        return match ($type) {
            'internal' => new InternalStrategy,
            'enter_app' => new EnterAppStrategy,
            'refresh_token', 'refresh_data', 'app_login' => new TokenRefreshStrategy,
            default => throw new \Exception('Tipo de autenticación no reconocido'),
        };
    }
}
