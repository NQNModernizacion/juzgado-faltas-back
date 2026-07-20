<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePruebaRequest;
use App\Http\Requests\UpdatePruebaRequest;
use App\Services\PruebaService;
use App\Http\Resources\PruebaResource;
use Illuminate\Support\Facades\Auth;
use DomainException;
use Throwable;

class PruebaController extends Controller
{
    public function __construct(
        protected PruebaService $pruebaService
    ) {}

    /**
     * Lista todas las pruebas asociadas a un acta específica.
     */
    public function listarPorActa(string $actaId)
    {
        try {
            $pruebas = $this->pruebaService->listarPruebasPorActa((int) $actaId);
            return sendResponse(PruebaResource::collection($pruebas));
        } catch (Throwable $e) {
            return error_response($e, __FUNCTION__);
        }
    }

    /**
     * Registra una nueva prueba (con o sin archivo físico).
     */
    public function store(StorePruebaRequest $request)
    {
        try {
            $prueba = $this->pruebaService->registrarPrueba($request->validated(), Auth::id() ?? 1);
            return sendResponse(new PruebaResource($prueba));
        } catch (DomainException $e) {
            return sendResponse(null, ['general' => $e->getMessage()], 422);
        } catch (Throwable $e) {
            return error_response($e, __FUNCTION__);
        }
    }

    /**
     * Muestra el detalle de una prueba, incluyendo su archivo físico en Base64.
     */
    public function show(string $id)
    {
        try {
            $prueba = $this->pruebaService->obtenerDetalleConArchivo((int) $id);
            return sendResponse(new PruebaResource($prueba));
        } catch (Throwable $e) {
            return error_response($e, __FUNCTION__);
        }
    }

    /**
     * Actualiza una prueba existente (permite cambiar metadatos, subir nuevo archivo o remover el actual).
     */
    public function update(UpdatePruebaRequest $request, string $id)
    {
        try {
            $prueba = $this->pruebaService->actualizarPrueba((int) $id, $request->validated(), Auth::id() ?? 1);
            return sendResponse(new PruebaResource($prueba));
        } catch (DomainException $e) {
            return sendResponse(null, ['general' => $e->getMessage()], 422);
        } catch (Throwable $e) {
            return error_response($e, __FUNCTION__);
        }
    }

    /**
     * Elimina lógicamente una prueba y su archivo polimórfico asociado.
     */
    public function destroy(string $id)
    {
        try {
            $this->pruebaService->eliminarPrueba((int) $id);
            return sendResponse(true);
        } catch (DomainException $e) {
            return sendResponse(null, ['general' => $e->getMessage()], 422);
        } catch (Throwable $e) {
            return error_response($e, __FUNCTION__);
        }
    }
}
