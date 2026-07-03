<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreEstadoProcesalRequest;
use App\Http\Requests\UpdateEstadoProcesalRequest;
use App\Http\Requests\RegistrarEstadoProcesalRequest;
use App\Services\EstadoProcesalService;
use App\Services\ActaService;
use App\Http\Resources\ActaResource;
use App\Http\Resources\EstadoProcesalResource;
use App\Models\EstadoProcesal;
use Illuminate\Http\Request;

class EstadoProcesalController extends Controller
{
    public function __construct(
        protected EstadoProcesalService $estadoProcesalService
    ) {}

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try {
            $estados = $this->estadoProcesalService->listarEstados($request->all());
            return sendResponse(EstadoProcesalResource::collection($estados));
        } catch (\Throwable $th) {
            return error_response($th, __FUNCTION__);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreEstadoProcesalRequest $request)
    {
        try {
            $estado = $this->estadoProcesalService->crearEstado($request->validated());
            return sendResponse(new EstadoProcesalResource($estado));
        } catch (\DomainException $e) {
            return sendResponse(null, ['general' => $e->getMessage()], 422);
        } catch (\Throwable $th) {
            return error_response($th, __FUNCTION__);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            $estado = EstadoProcesal::findOrFail($id);
            return sendResponse(new EstadoProcesalResource($estado));
        } catch (\DomainException $e) {
            return sendResponse(null, ['general' => $e->getMessage()], 422);
        } catch (\Throwable $th) {
            return error_response($th, __FUNCTION__);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateEstadoProcesalRequest $request, string $id)
    {
        try {
            $estado = $this->estadoProcesalService->actualizarEstado((int)$id, $request->validated());
            return sendResponse(new EstadoProcesalResource($estado));
        } catch (\DomainException $e) {
            return sendResponse(null, ['general' => $e->getMessage()], 422);
        } catch (\Throwable $th) {
            return error_response($th, __FUNCTION__);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $this->estadoProcesalService->eliminarEstado((int)$id);
            return sendResponse(true);
        } catch (\DomainException $e) {
            return sendResponse(null, ['general' => $e->getMessage()], 422);
        } catch (\Throwable $th) {
            return error_response($th, __FUNCTION__);
        }
    }

    /**
     * Registra un cambio de estado procesal en un acta.
     */
    public function registrarEstadoProcesal(RegistrarEstadoProcesalRequest $request, ActaService $actaService)
    {
        try {
            $data = $request->validated();
            $actaService->registrarEstadoProcesal($data['acta_id'], $data);

            // Obtener el detalle completo del acta tal como lo hace el show
            // $actaDetalle = $actaService->obtenerDetalleActa($data['acta_id']);

            // return sendResponse(new ActaResource($actaDetalle));

            // Obtener el historial completo y actualizado de estados procesales del acta
            $estados = $actaService->obtenerHistorialEstadosProcesales($data['acta_id']);

            return sendResponse(EstadoProcesalResource::collection($estados));
        } catch (\DomainException $e) {
            return sendResponse(null, ['general' => $e->getMessage()], 422);
        } catch (\Throwable $e) {
            return error_response($e, __FUNCTION__);
        }
    }

    /**
     * Obtiene el historial de estados procesales de un acta ordenada de la más reciente a la más antigua.
     */
    public function obtenerEstadosProcesales($id, ActaService $actaService)
    {
        try {
            $estados = $actaService->obtenerHistorialEstadosProcesales($id);
            return sendResponse(EstadoProcesalResource::collection($estados));
        } catch (\DomainException $e) {
            return sendResponse(null, ['general' => $e->getMessage()], 422);
        } catch (\Throwable $e) {
            return error_response($e, __FUNCTION__);
        }
    }
}
