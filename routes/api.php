<?php

use App\Http\Controllers\ActaController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DatosActaController;
use App\Http\Controllers\GrupoActaController;
use App\Http\Controllers\MovimientoController;
use App\Http\Controllers\PDFController;
use App\Http\Controllers\PadronController;
use App\Http\Controllers\DocumentoLegalController;
use App\Http\Controllers\InfractorController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PlantillaDocumentoController;


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
    Route::get('bootstrap', [AdminController::class, 'index'])->middleware('permission:admin.permission.view,sanctum');

    // Logs de Rate Limit
    Route::middleware('permission:admin.permission.view,sanctum')->group(function () {
        Route::get('logs/rate-limit/dates', [AdminController::class, 'getRateLimitDates']);
        Route::get('logs/rate-limit', [AdminController::class, 'getRateLimitLogs']);
        Route::delete('logs/rate-limit', [AdminController::class, 'deleteRateLimitLog']);
    });

    Route::get('get_person_info/{dni}', [AdminController::class, 'getPersonInfoByDni']);
    Route::post('users/{user}/sync-roles', [AdminController::class, 'syncRoles']);
    Route::post('roles/{role}/sync-permissions', [AdminController::class, 'syncRolePermissions']);

    Route::get('user-by-dni/{dni}', [AdminController::class, 'userByDni']);
    Route::post('users/{user}/sync-permissions', [AdminController::class, 'syncPermissions']);
});

/* Ruta para probar si se genera el pdf */
Route::get('prueba_pdf', [PDFController::class, 'pdf']);
// });

/** MOVER AL MIDDLEWARE */
Route::get('datos_acta', [DatosActaController::class, 'index']);
Route::get('datos_acta/{oficina}', [DatosActaController::class, 'getDatosPorOficina']);
Route::post('registrar_acta', [ActaController::class, 'store']);
Route::post('agrupar_actas', [GrupoActaController::class, 'agrupar']);
Route::post('agregar_a_grupo', [GrupoActaController::class, 'añadirAGrupo']);
Route::post('desagrupar_actas', [GrupoActaController::class, 'desagrupar']);
Route::get('actas', [ActaController::class, 'index'])->name('actas.index');
Route::get('actas/{id}', [ActaController::class, 'show'])->name('actas.show');
Route::put('actas/{id}', [ActaController::class, 'update'])->name('actas.update');
Route::get('grupos_actas', [GrupoActaController::class, 'index']);
Route::get('grupos_actas/{id}', [GrupoActaController::class, 'show'])->name('grupos_actas.show');
Route::get('grupos_de_acta/{acta_id}', [GrupoActaController::class, 'grupo_por_acta'])->name('grupo_de_acta');
Route::post('mover_causa', [MovimientoController::class, 'moverCausa']);
Route::get('actas/{id}/movimientos', [MovimientoController::class, 'getByActa']);
Route::get('consultar_padron', [PadronController::class, 'consultar']);
Route::get('consultar_imputado', [InfractorController::class, 'consultarImputado']);

// Rutas de Documentos Legales
Route::apiResource('documentos', DocumentoLegalController::class);
Route::get('documentos/{id}/pdf', [DocumentoLegalController::class, 'generarPdf']);

// Rutas de Plantillas de Documentos
Route::get('plantillas', [PlantillaDocumentoController::class, 'index']);
Route::post('plantillas', [PlantillaDocumentoController::class, 'store']);
Route::get('plantillas/{id}', [PlantillaDocumentoController::class, 'show']);
Route::put('plantillas/{id}', [PlantillaDocumentoController::class, 'update']);
Route::delete('plantillas/{id}', [PlantillaDocumentoController::class, 'destroy']);
