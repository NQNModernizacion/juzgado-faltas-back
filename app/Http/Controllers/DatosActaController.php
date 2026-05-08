<?php

namespace App\Http\Controllers;

use App\Http\Resources\OficinaResource;
use App\Models\Oficina;
use App\Services\DatosActaService;
use App\Services\OficinaService;
use Illuminate\Http\Request;

class DatosActaController extends Controller
{
    public function __construct(
        protected DatosActaService $datosActaService,
        protected OficinaService $oficinaService
    ) {}

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $datos = $this->datosActaService->getDatosIniciales();

            return sendResponse([
                'oficinas' => OficinaResource::collection($datos['oficinas']),
                'combos'   => $datos['combos']
            ]);
        } catch (\Throwable $th) {
            return error_response($th, __FUNCTION__);
        }
    }

    /**
     * Obtiene los datos específicos de una oficina junto con los combos generales.
     */
    public function getDatosPorOficina(Oficina $oficina)
    {
        try {
            $oficinaHabilitada = $this->oficinaService->cargarInspectoresHabilitados($oficina);

            return sendResponse([
                'oficina' => new OficinaResource($oficinaHabilitada),
                'combos'  => $this->datosActaService->getCombosGeneral()
            ]);
        } catch (\Throwable $th) {
            return error_response($th, __FUNCTION__);
        }
    }
}
