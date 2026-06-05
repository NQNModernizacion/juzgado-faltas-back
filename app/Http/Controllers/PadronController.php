<?php

namespace App\Http\Controllers;

use App\Models\Padron;
use App\Http\Requests\StorePadronRequest;
use App\Http\Requests\UpdatePadronRequest;
use App\Http\Requests\ConsultarPadronRequest;
use DomainException;
use Throwable;

class PadronController extends Controller
{
    /**
     * Consulta información de un padrón según su tipo e identificación.
     *
     * @param ConsultarPadronRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function consultar(ConsultarPadronRequest $request)
    {
        try {
            $tipo = $request->input('tipo');
            $identificacion = $request->input('identificacion');

            if ($tipo === 'AUT' || $tipo === 'MOT') {
                $data = consultar_aut_externo($identificacion);
                return sendResponse($data);
            }

            // Datos mock/simulados para los tipos que el usuario armará después
            $data = [];
            if ($tipo === 'ZOO') {
                $data = [
                    'identificacion' => $identificacion,
                    'tipo' => 'ZOO',
                    'nombre' => 'Mascota Mock',
                    'especie' => 'Canino',
                    'raza' => 'Labrador',
                    'propietario' => 'Juan Pérez',
                    'vacunacion_antirrabica' => true,
                    'simulado' => true
                ];
            } elseif ($tipo === 'INM') {
                $data = [
                    'identificacion' => $identificacion,
                    'tipo' => 'INM',
                    'nomenclatura_catastral' => '15-20-055-1234-0000',
                    'direccion' => 'Calle Falsa 123',
                    'propietario' => 'María López',
                    'deuda_activa' => false,
                    'simulado' => true
                ];
            } elseif ($tipo === 'COM') {
                $data = [
                    'identificacion' => $identificacion,
                    'tipo' => 'COM',
                    'razon_social' => 'Comercio Mock S.A.',
                    'cuit' => '30-12345678-9',
                    'rubro' => 'Supermercado',
                    'direccion' => 'Av. Argentina 500',
                    'estado_habilitacion' => 'Vigente',
                    'simulado' => true
                ];
            }

            return sendResponse($data);
        } catch (DomainException $e) {
            return sendResponse(null, ['general' => $e->getMessage()], 422);
        } catch (Throwable $e) {
            return error_response($e);
        }
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePadronRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Padron $padron)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePadronRequest $request, Padron $padron)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Padron $padron)
    {
        //
    }
}
