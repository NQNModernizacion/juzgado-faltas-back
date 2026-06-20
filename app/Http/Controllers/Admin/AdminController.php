<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Base\Permission;
use App\Models\Base\Role;
use App\Models\PersonasAdmin;
use App\Models\User;
use App\Models\UsuariosAdmin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Spatie\Permission\PermissionRegistrar;

class AdminController extends Controller
{
    public function index(Request $request)
    {
        $u = $request->user();

        // ✅ logs antes de cortar
        Log::info('[admin.bootstrap] incoming', [
            'auth_check' => Auth::check(),
            'user_id' => $u?->id,
            'email' => $u?->email,
            'defaults_guard' => config('auth.defaults.guard'),
            'token_id' => method_exists($u, 'currentAccessToken') ? ($u?->currentAccessToken()?->id) : null,
            'token_name' => method_exists($u, 'currentAccessToken') ? ($u?->currentAccessToken()?->name) : null,
        ]);

        return response()->json([
            'data' => [
                'roles' => Role::query()
                    ->orderBy('name')
                    ->get()
                    ->map(fn($r) => [
                        'id' => $r->id,
                        'description' => $r->name,
                        'name' => $r->name,
                        // opcional
                        // 'permissions' => $r->permissions()->select('id','name')->get(),
                    ])
                    ->values(),

                'permissions' => Permission::query()
                    ->orderBy('name')
                    ->get()
                    ->map(fn($p) => [
                        'id' => $p->id,
                        'name' => $p->name,
                        'description' => $p->description ?? $p->name,
                    ])
                    ->values(),

                // users are stored in `admin`, roles/permissions in `mysql`
                'users' => (function () {
                    $adminRoleUserIds = DB::connection('mysql')
                        ->table('model_has_roles as mhr')
                        ->join('roles as r', 'r.id', '=', 'mhr.role_id')
                        ->where('mhr.model_type', User::class)
                        ->where('r.name', 'admin')
                        ->pluck('mhr.model_id')
                        ->unique()
                        ->values();

                    if ($adminRoleUserIds->isEmpty()) {
                        return collect();
                    }

                    $rolesByUser = DB::connection('mysql')
                        ->table('model_has_roles as mhr')
                        ->join('roles as r', 'r.id', '=', 'mhr.role_id')
                        ->where('mhr.model_type', User::class)
                        ->whereIn('mhr.model_id', $adminRoleUserIds)
                        ->select(['mhr.model_id', 'r.name'])
                        ->get()
                        ->groupBy('model_id');

                    $permissionsByUser = DB::connection('mysql')
                        ->table('model_has_permissions as mhp')
                        ->join('permissions as p', 'p.id', '=', 'mhp.permission_id')
                        ->where('mhp.model_type', User::class)
                        ->whereIn('mhp.model_id', $adminRoleUserIds)
                        ->select(['mhp.model_id', 'p.name'])
                        ->get()
                        ->groupBy('model_id');

                    return User::query()
                        ->whereIn('id', $adminRoleUserIds)
                        ->select(['id', 'email'])
                        ->get()
                        ->map(function ($u) use ($rolesByUser, $permissionsByUser) {
                            return [
                                'id' => $u->id,
                                'email' => $u->email,
                                'roles' => ($rolesByUser->get($u->id) ?? collect())->pluck('name')->values(),
                                'permissions' => ($permissionsByUser->get($u->id) ?? collect())->pluck('name')->values(),
                            ];
                        })
                        ->values();
                })(),
            ],
            'error' => null,
        ]);
    }

    public function getPersonInfoByDni(Request $request, string $dni)
    {

        $dniClean = preg_replace('/\D+/', '', (string) $dni);

        if (!$dniClean || strlen($dniClean) < 7 || strlen($dniClean) > 10) {
            return response()->json([
                'error' => 'DNI no válido',
                'informacion' => null,
            ], 422);
        }

        $persona = PersonasAdmin::query()->where('documento', $dniClean)->first();

        if (!$persona) {
            return response()->json([
                'error' => 'No se encontró persona con ese DNI',
                'informacion' => null,
            ], 404);
        }

        $usuarioAdmin = UsuariosAdmin::query()
            ->where('PersonaID', $persona->id)
            ->first();

        $usuarioID = (int) ($usuarioAdmin?->ReferenciaID ?? 0);

        return response()->json([
            'error' => null,
            'informacion' => [
                'usuarioID' => $usuarioID,
                'documento' => (int) $persona->documento,
                'nombres' => $persona->nombres ?? null,
                'apellidos' => $persona->apellidos ?? null,
                'nombreCompleto' => trim(($persona->apellidos ?? '') . ', ' . ($persona->nombres ?? '')),
                'correoElectronico' => $persona->correoElectronico ?? null,
                'celular' => $persona->celular ?? null,
                'genero' => $persona->genero ?? null,
                'direccionCompleta' => $persona->direccionCompleta ?? null,
            ],
        ], 200);
    }

    public function syncRoles(Request $request, User $user)
    {
        $roleIds = $request->input('role_ids', null);
        $roles = $request->input('roles', null);

        if (is_array($roleIds)) {
            $names = Role::query()->whereIn('id', $roleIds)->pluck('name')->values()->all();
        } elseif (is_array($roles)) {
            $names = Role::query()->whereIn('name', $roles)->pluck('name')->values()->all();
        } else {
            return response()->json(['data' => null, 'error' => 'Enviar role_ids[] o roles[]'], 422);
        }

        if (!count($names)) {
            return response()->json(['data' => null, 'error' => 'Roles inválidos'], 422);
        }

        $user->assignRole($names);
        app(\Spatie\Permission\PermissionRegistrar::class)->forgetCachedPermissions();

        return response()->json([
            'data' => [
                'user_id' => $user->id,
                'roles' => $user->getRoleNames()->values(),
                'permissions' => $user->getAllPermissions()->pluck('name')->values(),
            ],
            'error' => null,
        ]);
    }

    public function syncPermissions(Request $request, User $user)
    {
        $actor = $request->user();
        abort_unless($actor && $actor->hasPermissionTo('admin.permission.asign', 'sanctum'), 403);

        $permIds = $request->input('permission_ids', []);
        if (!is_array($permIds)) {
            return response()->json(['data' => null, 'error' => 'permission_ids debe ser un array'], 422);
        }

        $names = Permission::query()->whereIn('id', $permIds)->pluck('name')->values()->all();

        $user->syncPermissions($names);

        app(PermissionRegistrar::class)->forgetCachedPermissions();

        return response()->json([
            'data' => [
                'user_id' => $user->id,
                'permissions' => $user->getAllPermissions()->pluck('name')->values(),
            ],
            'error' => null,
        ]);
    }

    public function syncRolePermissions(Request $request, Role $role)
    {
        $actor = $request->user();
        abort_unless($actor && $actor->hasPermissionTo('admin.role-permission.asign', 'sanctum'), 403);

        $permIds = $request->input('permission_ids', []);
        if (!is_array($permIds)) {
            return response()->json(['data' => null, 'error' => 'permission_ids debe ser un array'], 422);
        }

        $names = Permission::query()->whereIn('id', $permIds)->pluck('name')->values()->all();

        $role->syncPermissions($names);

        app(PermissionRegistrar::class)->forgetCachedPermissions();

        return response()->json([
            'data' => [
                'role_id' => $role->id,
                'permissions' => $role->permissions()->pluck('name')->values(),
            ],
            'error' => null,
        ]);
    }
    public function userByDni(Request $request, string $dni)
    {
        // Solo auth:sanctum (ya está en rutas). Sin permisos extra para no bloquear el buscador.
        // abort_unless($request->user()?->hasPermissionTo('admin.permission.view'), 403);

        $dniClean = preg_replace('/\D+/', '', $dni);

        if (!$dniClean || strlen($dniClean) < 7 || strlen($dniClean) > 10) {
            return response()->json(['error' => 'DNI no válido', 'data' => null], 422);
        }

        $persona = PersonasAdmin::query()
            ->select(['id', 'documento', 'nombres', 'apellidos'])
            ->where('documento', $dniClean)
            ->first();

        if (!$persona) {
            return response()->json(['error' => 'No se encontró persona', 'data' => null], 404);
        }

        $usuarioAdmin = UsuariosAdmin::query()
            ->select(['ReferenciaID'])
            ->where('PersonaID', $persona->id)
            ->first();

        $userId = (int) ($usuarioAdmin?->ReferenciaID ?? 0);

        if (!$userId) {
            return response()->json([
                'error' => 'La persona no tiene usuario en users',
                'data' => [
                    'persona' => [
                        'dni' => (int) $persona->documento,
                        'nombres' => $persona->nombres,
                        'apellidos' => $persona->apellidos,
                        'user_id' => 0,
                    ],
                    'user' => null,
                ],
            ], 200);
        }

        $user = User::query()->find($userId);

        if (!$user) {
            return response()->json([
                'error' => 'ReferenciaID no existe en users',
                'data' => [
                    'persona' => [
                        'dni' => (int) $persona->documento,
                        'nombres' => $persona->nombres,
                        'apellidos' => $persona->apellidos,
                        'user_id' => $userId,
                    ],
                    'user' => null,
                ],
            ], 409);
        }
        return response()->json([
            'error' => null,
            'data' => [
                'persona' => [
                    'dni' => (int) $persona->documento,
                    'nombres' => $persona->nombres,
                    'apellidos' => $persona->apellidos,
                    'user_id' => $user->id,
                ],
                'user' => [
                    'id' => $user->id,
                    'email' => $user->email,
                    'roles' => $user->getRoleNames()->values(),
                    'permissions' => $user->getAllPermissions()->pluck('name')->values(),
                ],
            ],
        ], 200);
    public function getRateLimitDates(Request $request)
    {
        $files = \Illuminate\Support\Facades\File::files(storage_path('logs'));
        $dates = [];
        
        foreach ($files as $file) {
            if (preg_match('/rate_limit-(\d{4}-\d{2}-\d{2})\.log/', $file->getFilename(), $matches)) {
                $dates[] = $matches[1];
            }
        }
        
        rsort($dates);
        return sendResponse($dates, null, 200);
    }

    public function getRateLimitLogs(Request $request)
    {
        $limit = (int) $request->input('limit', 100);
        $date = $request->input('date', now()->format('Y-m-d'));
        
        if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $date)) {
            return sendResponse(null, 'Debe proveer una fecha válida (YYYY-MM-DD)', 422);
        }

        $path = storage_path("logs/rate_limit-{$date}.log");

        if (!\Illuminate\Support\Facades\File::exists($path)) {
            return sendResponse([], null, 200);
        }

        $lines = [];
        if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
            exec('powershell -command "Get-Content ' . escapeshellarg($path) . ' -Tail ' . $limit . '"', $lines);
        } else {
            exec("tail -n {$limit} " . escapeshellarg($path), $lines);
        }

        // Tail devuelve en el mismo orden del archivo, los damos vuelta para ver los más nuevos arriba
        $lines = array_reverse($lines);
        
        $logs = [];
        foreach ($lines as $line) {
            $data = json_decode($line, true);
            if ($data) {
                $logs[] = $data;
            }
        }

        return sendResponse($logs, null, 200);
    }

    public function deleteRateLimitLog(Request $request)
    {
        $date = $request->input('date');
        
        if (!$date || !preg_match('/^\d{4}-\d{2}-\d{2}$/', $date)) {
            return sendResponse(null, 'Debe proveer una fecha válida (YYYY-MM-DD)', 422);
        }

        $path = storage_path("logs/rate_limit-{$date}.log");

        if (\Illuminate\Support\Facades\File::exists($path)) {
            \Illuminate\Support\Facades\File::delete($path);
        }

        return sendResponse(['message' => "El log del día {$date} ha sido eliminado."], null, 200);
    }
}
