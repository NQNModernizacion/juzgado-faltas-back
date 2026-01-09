<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\AuthRequest;
use App\Models\PersonasAdmin;
use App\Models\User;
use App\Models\UsuariosAdmin;
use Exception;
use Illuminate\Auth\AuthenticationException;
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

    public function login(AuthRequest $request)
    {
        try {
            // dd([
            //     'bearer' => $request->bearerToken(),
            //     'guard' => config('auth.guards.sanctum'),
            //     'user' => $request->user('sanctum')?->id,
            // ]);

            $data = $request->all();
            // $data = $request->validate([
            //     'type' => ['required', 'string'],
            //     '_id' => ['required', 'string'],
            //     'password' => ['required', 'string'],
            //     /* 'email' => ['required', 'email'],
            //     'password' => ['required', 'string'], */
            //     'device_name' => ['nullable', 'string', 'max:255'],
            // ]);
            switch ($request->type) {
                case 'internal':
                    if (is_email($request->_id)) {
                        // $user = User::where('email', $request->_id)->first();
                        $user = User::where('email', $data['_id'])->first();
                    } else {
                        $person = PersonasAdmin::where('documento', $request->_id)->first();
                        if (!$person) {
                            throw new Exception('Persona no encontrada');
                        } else {
                            $user = $person->UsuarioAdmin;
                            if (!$user) {
                                throw new Exception('Usuario no encontrado');
                            }
                            $user = $user->users;
                        }
                        // throw new Exception('Usuario no encontrado');
                    }
                    // Cuando _id es DNI -- HAY QUE RESOLVERLO CON PERSONA MODEL
                    break;

                case 'enter_app':
                    $user = User::find($request->_id);
                    break;

                case 'refresh_data':
                case 'refresh_token':
                case 'app_login':
                    //validacion de existencia del token
                    if (
                        $request->header('Authorization') == null ||
                        $request->header('Authorization') == 'Bearer undefined' ||
                        $request->header('Authorization') == 'Bearer null' ||
                        $request->header('Authorization') == 'Bearer '
                    ) {
                        throw new Exception('No se ha proporcionado un token');
                    }
                    /* AuthService::checkToken($request->header('Authorization'));
                    $user = User::find(Auth::id()); */
                    // $user = $request->user();

                    // fuerza a Sanctum a validar el Bearer del request
                    $user = $request->user('sanctum'); // o auth('sanctum')->user();

                    if (!$user) {
                        throw new Exception('No se ha proporcionado un token');
                        // return response()->json(['message' => 'Unauthenticated.'], 401);
                    }

                    // si querés también asegurar que hay token actual:
                    if (!$user->currentAccessToken()) {
                        throw new Exception('No se ha proporcionado un token');
                        // return response()->json(['message' => 'Unauthenticated.'], 401);
                    }

                    $token = $user->currentAccessToken(); // token actual autenticado :contentReference[oaicite:9]{index=9}
                    $token->delete();

                    break;

                default:
                    //En ningún caso debería llegar hasta acá
                    // $user = User::find($request->_id);
                    throw new Exception('Tipo de autenticación no reconocido');

                    break;
            }
            /* dd($user->userAdmin->persona->only([
                'id',
                'documento',
                'nombres',
                'apellidos',
                'nombreCompleto',
                'genero',
                'celular',
                'correoElectronico',
                'direccionCompleta',
            ])); */
            if (array_key_exists('password', $data)) {
                if (! $user || ! Hash::check($data['password'], $user->password)) {
                    // Activitylog: podés registrar intento fallido sin causer si querés (opcional)
                    return sendResponse(null, 'Credenciales inválidas', 422);
                    // return response()->json(['message' => 'Credenciales inválidas'], 422);
                }
            }

            $deviceName = $data['device_name']
                ?? substr(($request->userAgent() ?: 'api-device'), 0, 255);

            // Sanctum: createToken() devuelve plainTextToken para devolver al cliente :contentReference[oaicite:7]{index=7}
            $plain = $user->createToken($deviceName)->plainTextToken;

            // Activitylog: ejemplo de uso con causedBy/withProperties :contentReference[oaicite:8]{index=8}
            // activity('auth')
            //     ->causedBy($user)
            //     ->withProperties(['device_name' => $deviceName, 'ip' => $request->ip()])
            //     ->log('login');
            return sendResponse([
                'token_type' => 'Bearer',
                'token' => $plain,
                'expires_at' => $this->expiresAtFromNow(),
                'user' => [
                    'id' => $user->id,
                    'email' => $user->email,
                    "auth_method" => $request['type'],
                    'persona' => $user->userAdmin->persona->only([
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
                ],
            ], null, 200);
            // return response()->json([
            //     'token_type' => 'Bearer',
            //     'access_token' => $plain,
            //     'expires_at' => $this->expiresAtFromNow(),
            //     'user' => [
            //         'id' => $user->id,
            //         'email' => $user->email,
            //         'roles' => $user->getRoleNames(),
            //         'permissions' => $user->getAllPermissions()->pluck('name'),
            //     ],
            // ]);
        } catch (\Throwable $th) {
            //throw $th;
            $log = saveLog($th, 'error', __FUNCTION__);
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
