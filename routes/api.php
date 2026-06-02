<?php

use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PDFController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/* Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum'); */

// Route::prefix('auth')->group(function () {
Route::post('auth', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('refresh', [AuthController::class, 'refresh']); // a demanda
    Route::post('logout', [AuthController::class, 'logout']);
    Route::get('me', [AuthController::class, 'me']);
});


Route::middleware('auth:sanctum')->prefix('admin')->group(function () {
    Route::get('bootstrap', [AdminController::class, 'index']);
   Route::get('get_person_info/{dni}', [AdminController::class, 'getPersonInfoByDni']);
    Route::post('users/{user}/sync-roles', [AdminController::class, 'syncRoles']);
    Route::post('roles/{role}/sync-permissions', [AdminController::class, 'syncRolePermissions']);
    
    Route::get('user-by-dni/{dni}', [AdminController::class, 'userByDni']);
    Route::post('users/{user}/sync-permissions', [AdminController::class, 'syncPermissions']);
});

// TODO: eliminar — diagnóstico de trusted proxies
Route::get('proxy-check', function (Request $request) {
    return response()->json([
        'ip_detectada'        => $request->ip(),
        'x_forwarded_for'     => $request->header('X-Forwarded-For'),
        'x_forwarded_proto'   => $request->header('X-Forwarded-Proto'),
        'x_forwarded_host'    => $request->header('X-Forwarded-Host'),
        'proxy_confiado'      => in_array($request->server('REMOTE_ADDR'), ['192.168.35.21']) ? 'SÍ' : 'NO (IP real: ' . $request->server('REMOTE_ADDR') . ')',
    ]);
});

/* Ruta para probar si se genera el pdf */
Route::get('prueba_pdf', [PDFController::class, 'pdf']);
// });
