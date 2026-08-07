<?php

namespace App\Http\Controllers;

use App\Models\Padron;
use App\Models\EstadosGenerales;
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

            // 1. Resolver el ID del tipo de padrón en estados_generales
            $tipoEstado = EstadosGenerales::where('label', 'TIPO_PADRON')
                ->where('value', $tipo)
                ->first();

            $tipoId = $tipoEstado ? $tipoEstado->id : null;

            // 2. Buscar si existe en la base de datos local
            $padron = null;
            if ($tipoId) {
                $padron = Padron::where('tipo_id', $tipoId)
                    ->where('identificacion', $identificacion)
                    ->first();
            }

            // 3. Verificar si la caché está vigente
            $cacheDias = (int) env('CACHE_PADRON_DIAS', 30);
            $fechaLimite = now()->subDays($cacheDias);

            $tieneCacheValida = $padron 
                && $padron->fecha_actualizacion 
                && $padron->fecha_actualizacion->gt($fechaLimite)
                && !empty($padron->data_cache);

            if ($tieneCacheValida) {
                return sendResponse($padron->data_cache);
            }

            // 4. Si no tiene caché válida, hacer la consulta externa con try-catch para fallback
            $data = [];
            try {
                if ($tipo === 'AUT' || $tipo === 'MOT') {
                    $data = consultar_aut_externo($identificacion);
                } elseif ($tipo === 'ZOO') {
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

                // 5. Si la consulta fue exitosa, almacenar o actualizar la caché
                if (!empty($data) && $tipoId) {
                    $nombreLocal = $data['nombre'] 
                        ?? $data['razon_social'] 
                        ?? ($data['titular']['nombre'] ?? null) 
                        ?? ($padron->nombre ?? 'PADRON ' . $identificacion);

                    $padron = Padron::updateOrCreate(
                        [
                            'tipo_id' => $tipoId,
                            'identificacion' => $identificacion,
                        ],
                        [
                            'nombre' => $nombreLocal,
                            'data_cache' => $data,
                            'fecha_actualizacion' => now(),
                        ]
                    );
                }

                return sendResponse($data);

            } catch (Throwable $e) {
                // Fallback de integridad: si falla la API pero tenemos una caché expirada, la servimos
                if ($padron && !empty($padron->data_cache)) {
                    \Illuminate\Support\Facades\Log::warning("Fallo en API externa para padrón {$identificacion}, se retorna caché expirada: " . $e->getMessage());
                    return sendResponse($padron->data_cache);
                }
                throw $e;
            }

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
