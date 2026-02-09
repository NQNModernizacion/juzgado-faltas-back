<?php

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

/* Ruta para probar si se genera el pdf */
 Route::get('prueba_pdf',[PDFController::class,'pdf']);
// });
