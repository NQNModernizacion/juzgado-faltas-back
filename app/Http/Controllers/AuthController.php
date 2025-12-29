<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    private function tokenExpirationMinutes(): int
    {
        $exp = config('sanctum.expiration');

        // En este template asumimos expiración global siempre activa
        return (int) $exp;
    }

    private function expiresAtFromNow(): string
    {
        return now()->addMinutes($this->tokenExpirationMinutes())->toISOString();
    }

    public function login(Request $request)
    {
        $data = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
            'device_name' => ['nullable', 'string', 'max:255'],
        ]);

        $user = User::where('email', $data['email'])->first();

        if (! $user || ! Hash::check($data['password'], $user->password)) {
            // Activitylog: podés registrar intento fallido sin causer si querés (opcional)
            return response()->json(['message' => 'Credenciales inválidas'], 422);
        }

        $deviceName = $data['device_name']
            ?? substr(($request->userAgent() ?: 'api-device'), 0, 255);

        // Sanctum: createToken() devuelve plainTextToken para devolver al cliente :contentReference[oaicite:7]{index=7}
        $plain = $user->createToken($deviceName)->plainTextToken;

        // Activitylog: ejemplo de uso con causedBy/withProperties :contentReference[oaicite:8]{index=8}
        activity('auth')
            ->causedBy($user)
            ->withProperties(['device_name' => $deviceName, 'ip' => $request->ip()])
            ->log('login');

        return response()->json([
            'token_type' => 'Bearer',
            'access_token' => $plain,
            'expires_at' => $this->expiresAtFromNow(),
            'user' => [
                'id' => $user->id,
                'email' => $user->email,
                'roles' => $user->getRoleNames(),
                'permissions' => $user->getAllPermissions()->pluck('name'),
            ],
        ]);
    }

    public function me(Request $request)
    {
        $user = $request->user();

        return response()->json([
            'id' => $user->id,
            'email' => $user->email,
            'roles' => $user->getRoleNames(),
            'permissions' => $user->getAllPermissions()->pluck('name'),
        ]);
    }

    public function refresh(Request $request)
    {
        $user = $request->user();
        $token = $user->currentAccessToken(); // token actual autenticado :contentReference[oaicite:9]{index=9}

        $expMinutes = $this->tokenExpirationMinutes();
        $expiresAt = $token->created_at->copy()->addMinutes($expMinutes);
        $secondsLeft = now()->diffInSeconds($expiresAt, false);

        $refreshBefore = (int) env('SANCTUM_REFRESH_BEFORE_MINUTES', 10) * 60;

        // Si todavía falta “mucho”, no rotamos (refresh a demanda)
        if ($secondsLeft > $refreshBefore) {
            return response()->json([
                'refreshed' => false,
                'expires_at' => $expiresAt->toISOString(),
                'seconds_left' => $secondsLeft,
            ]);
        }

        // Revocar token actual (Sanctum recomienda borrando tokens) :contentReference[oaicite:10]{index=10}
        $token->delete();

        $deviceName = substr(($request->userAgent() ?: 'api-device'), 0, 255);
        $plain = $user->createToken($deviceName)->plainTextToken;

        activity('auth')
            ->causedBy($user)
            ->withProperties(['device_name' => $deviceName, 'ip' => $request->ip()])
            ->log('refresh');

        return response()->json([
            'refreshed' => true,
            'token_type' => 'Bearer',
            'access_token' => $plain,
            'expires_at' => $this->expiresAtFromNow(),
        ]);
    }

    public function logout(Request $request)
    {
        $user = $request->user();

        // Revocar el token usado en este request :contentReference[oaicite:11]{index=11}
        $user->currentAccessToken()->delete();

        activity('auth')
            ->causedBy($user)
            ->withProperties(['ip' => $request->ip()])
            ->log('logout');

        return response()->json(['ok' => true]);
    }
}
