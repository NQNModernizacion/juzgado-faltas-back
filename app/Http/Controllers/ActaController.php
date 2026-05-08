<?php

namespace App\Http\Controllers;

use App\Http\Requests\AgruparActasRequest;
use App\Models\Acta;
use App\Http\Requests\StoreActaRequest;
use App\Http\Requests\UpdateActaRequest;
use App\Models\GrupoActa;
use App\Models\Padron;
use App\Models\Infractor;
use App\Models\Juez;
use App\Models\Juzgado;
use App\Models\Movimiento;
use App\Models\OficinaInterna;
use App\Models\Secretaria;
use App\Services\ActaService;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class ActaController extends Controller
{
    public function __construct(
        protected ActaService $actaService
    ) {}

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreActaRequest $request)
    {
        try {
            $acta = $this->actaService->registrarActa($request->validated());

            return sendResponse($acta->load('grupo', 'padrones', 'infractores', 'infracciones'));
        } catch (\Throwable $e) {
            return error_response($e);
        }
    }

    /**
     * Agrupar múltiples actas.
     */
    public function agrupar(AgruparActasRequest $request)
    {
        try {
            $grupo = $this->actaService->agruparActas($request->acta_ids);

            return sendResponse($grupo, null, 200);
        } catch (\DomainException $e) {
            return sendResponse(null, $e->getMessage(), 422);
        } catch (\Throwable $e) {
            saveLog($e);
            return error_response($e);
        }
    }
}
