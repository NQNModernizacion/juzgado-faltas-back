<?php

namespace App\Http\Controllers;

use App\Models\Acta;
use App\Http\Requests\StoreActaRequest;
use App\Http\Requests\UpdateActaRequest;
use App\Http\Resources\ActaResource;
use App\Services\ActaService;
use Carbon\Carbon;
use Illuminate\Http\Request;
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

            return sendResponse(new ActaResource($acta->load('grupo', 'padrones', 'infractores', 'infracciones')));
        } catch (\DomainException $e) {
            return sendResponse(null, ['general' => $e->getMessage()], 422);
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
            $actas = $this->actaService->obtenerListadoActas($request->all());

            return sendResponse(ActaResource::collection($actas)->response()->getData(true));
        } catch (\Throwable $e) {
            return error_response($e, __FUNCTION__);
        }
    }

    public function update(UpdateActaRequest $request, $id)
    {
        try {
            $acta = $this->actaService->actualizarActa($id, $request->validated());

            return sendResponse(new ActaResource($acta->load('grupo', 'padrones', 'infractores', 'infracciones')));
        } catch (\DomainException $e) {
            return sendResponse(null, ['general' => $e->getMessage()], 422);
        } catch (\Throwable $e) {
            return error_response($e, __FUNCTION__);
        }
    }

    public function show($id)
    {
        try {
            $acta = $this->actaService->obtenerDetalleActa($id);

            return sendResponse(new ActaResource($acta));
        } catch (\DomainException $e) {
            return sendResponse(null, ['general' => $e->getMessage()], 422);
        } catch (\Throwable $e) {
            return error_response($e, __FUNCTION__);
        }
    }
}
