<?php

use App\Http\Controllers\Admin\AdminBootstrapController;
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
    Route::get('bootstrap', [AdminBootstrapController::class, 'index']);
   Route::get('get_person_info/{dni}', [AdminBootstrapController::class, 'getPersonInfoByDni']);
    Route::post('users/{user}/sync-roles', [AdminBootstrapController::class, 'syncRoles']);
    Route::post('roles/{role}/sync-permissions', [AdminBootstrapController::class, 'syncRolePermissions']);
    
    Route::get('user-by-dni/{dni}', [AdminBootstrapController::class, 'userByDni']);
    Route::post('users/{user}/sync-permissions', [AdminBootstrapController::class, 'syncPermissions']);
});

/* Ruta para probar si se genera el pdf */
Route::get('prueba_pdf', [PDFController::class, 'pdf']);
// });
