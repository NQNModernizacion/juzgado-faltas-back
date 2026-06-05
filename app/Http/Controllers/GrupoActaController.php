<?php

namespace App\Http\Controllers;

use App\Http\Requests\AgruparActasRequest;
use App\Http\Requests\AñadirAGrupoRequest;
use App\Http\Requests\DesagruparActasRequest;
use App\Http\Resources\GrupoActaResource;
use App\Services\GrupoService;
use Illuminate\Http\Request;

class GrupoActaController extends Controller
{
    public function __construct(
        protected GrupoService $grupoService
    ) {}

    /**
     * Agrupar múltiples actas.
     */
    public function agrupar(AgruparActasRequest $request)
    {
        try {
            $grupo = $this->grupoService->agruparActas($request->actas);

            return sendResponse(new GrupoActaResource($grupo), null, 200);
        } catch (\DomainException $e) {
            return sendResponse(null, ['message' => $e->getMessage()], 422);
        } catch (\Throwable $e) {
            return error_response($e, __FUNCTION__);
        }
    }

    /**
     * Añadir actas a un grupo existente.
     */
    public function añadirAGrupo(AñadirAGrupoRequest $request)
    {
        try {
            $grupo = $this->grupoService->añadirAGrupo($request->grupo_id, $request->actas);

            return sendResponse(new GrupoActaResource($grupo), null, 200);
        } catch (\DomainException $e) {
            return sendResponse(null, ['message' => $e->getMessage()], 422);
        } catch (\Throwable $e) {
            return error_response($e, __FUNCTION__);
        }
    }

    /**
     * Quitar actas de sus grupos.
     */
    public function desagrupar(DesagruparActasRequest $request)
    {
        try {
            $this->grupoService->desagruparActas($request->actas);

            return sendResponse(null, 'Actas desagrupadas correctamente', 200);
        } catch (\DomainException $e) {
            return sendResponse(null, ['message' => $e->getMessage()], 422);
        } catch (\Throwable $e) {
            return error_response($e, __FUNCTION__);
        }
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try {
            $grupos = $this->grupoService->listarGrupos($request->all());

            return sendResponse(GrupoActaResource::collection($grupos), null, 200);
        } catch (\Throwable $e) {
            return error_response($e, __FUNCTION__);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        try {
            $grupo = $this->grupoService->obtenerDetalleGrupo($id);

            return sendResponse(new GrupoActaResource($grupo), null, 200);
        } catch (\DomainException $e) {
            return sendResponse(null, ['message' => $e->getMessage()], 422);
        } catch (\Throwable $e) {
            return error_response($e, __FUNCTION__);
        }
    }
    /**
     * Display the specified resource.
     */
    public function grupo_por_acta($acta_id)
    {
        try {
            $grupo = $this->grupoService->obtenerGrupoByActa($acta_id);

            return sendResponse(new GrupoActaResource($grupo), null, 200);
        } catch (\DomainException $e) {
            return sendResponse(null, ['message' => $e->getMessage()], 404);
        } catch (\Throwable $e) {
            return error_response($e, __FUNCTION__);
        }
    }
}
