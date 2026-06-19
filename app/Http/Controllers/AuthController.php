<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Strategies\Auth\Strategies\AuthStrategyResolver;
use App\Http\Requests\AuthRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    private function userPayload($user, $type)
    {
        return [
            'id' => $user->id,
            'email' => $user->email,
            'auth_method' => $type,
            'persona' => $user->userAdmin?->persona?->only([
                'id',
                'documento',
                'nombres',
                'apellidos',
                'nombreCompleto',
                'genero',
                'celular',
                'correoElectronico',
                'direccionCompleta',
            ]),
            'roles' => $user->getRoleNames(),
            'permissions' => $user->getAllPermissions()->pluck('name'),
            'token_weblogin' => $user->token_weblogin,
        ];
    }

    private function tokenExpirationMinutes(): int
    {
        $exp = config('sanctum.expiration');

        // En este template asumimos expiración global siempre activa
        return (int) $exp;
    }

    private function expiresAtFromNow(): string
    {
        return now()->addMinutes($this->tokenExpirationMinutes())->toIso8601String();
    }

    public function login(AuthRequest $request)
    {
        try {
            $data = $request->all();

            $strategy = AuthStrategyResolver::resolve($request->type);
            $user = $strategy->authenticate($request);

            if (array_key_exists('password', $data)) {
                if (! Hash::check($data['password'], $user->password)) {
                    return sendResponse(null, 'Credenciales inválidas', 422);
                }
            }

            $deviceName = $data['device_name']
                ?? substr(($request->userAgent() ?: 'api-device'), 0, 255);

            $token = $user->createToken($deviceName)->plainTextToken;
            if ($request->type != 'internal') {
                register_app_income(Auth::user()->id, $request);
            }
            return sendResponse([
                'token_type' => 'Bearer',
                'token' => $token,
                'expires_at' => $this->expiresAtFromNow(),
                'user' => $this->userPayload($user, $request->type),
            ], null, 200);
        } catch (\Throwable $th) {
            saveLog($th, 'error', __FUNCTION__);

            return sendResponse(null, $th->getMessage(), 490);
        }
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
            return sendResponse([
                'token_type' => 'Bearer',
                'user' => $this->userPayload($user, $request->type),
                'refreshed' => false,
                'expires_at' => $expiresAt->toIso8601String(),
                'seconds_left' => $secondsLeft,
            ], null, 200);
        }

        // Revocar token actual (Sanctum recomienda borrando tokens) :contentReference[oaicite:10]{index=10}
        $token->delete();

        $deviceName = substr(($request->userAgent() ?: 'api-device'), 0, 255);
        $plain = $user->createToken($deviceName)->plainTextToken;

        activity('auth')
            ->causedBy($user)
            ->withProperties(['device_name' => $deviceName, 'ip' => $request->ip()])
            ->log('refresh');

        return sendResponse([
            'token_type' => 'Bearer',
            'token' => $plain,
            'expires_at' => $this->expiresAtFromNow(),
            'user' => $this->userPayload($user, $request->type),
            'refreshed' => true,
        ], null, 200);
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
