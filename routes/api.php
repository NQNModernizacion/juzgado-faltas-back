<?php

use App\Http\Controllers\ActaController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DatosActaController;
use App\Http\Controllers\GrupoActaController;
use App\Http\Controllers\MovimientoController;
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

/* Ruta para probar si se genera el pdf */
Route::get('prueba_pdf', [PDFController::class, 'pdf']);
// });

Route::get('datos_acta', [DatosActaController::class, 'index']);
Route::get('datos_acta/{oficina}', [DatosActaController::class, 'getDatosPorOficina']);
Route::post('registrar_acta', [ActaController::class, 'store']);
Route::post('agrupar_actas', [GrupoActaController::class, 'agrupar']);
Route::post('añadir_a_grupo', [GrupoActaController::class, 'añadirAGrupo']);
Route::post('desagrupar_actas', [GrupoActaController::class, 'desagrupar']);
Route::get('actas', [ActaController::class, 'index'])->name('actas.index');
Route::get('actas/{id}', [ActaController::class, 'show']);
Route::get('grupos_actas', [GrupoActaController::class, 'index']);
Route::get('grupos_actas/{id}', [GrupoActaController::class, 'show']);
Route::post('mover_causa', [MovimientoController::class, 'moverCausa']);
