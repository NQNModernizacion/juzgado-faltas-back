<?php

namespace App\Http\Controllers;

use App\Http\Requests\ConsultarInfractorRequest;
use App\Models\Infractor;
use App\Models\EstadosGenerales;
use App\Http\Requests\StoreInfractorRequest;
use App\Http\Requests\UpdateInfractorRequest;
use App\Models\PersonasAdmin;
use DomainException;
use Throwable;

class InfractorController extends Controller
{
    /**
     * Consulta información de un infractor según su tipo e identificación.
     *
     * @param ConsultarInfractorRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function consultarImputado(ConsultarInfractorRequest $request)
    {
        try {
            $tipo = $request->input('tipo');
            $identificacion = $request->input('identificacion');

            // 1. Intentar consulta directa en la base de datos interna (PersonasAdmin)
            $personaAdmin = null;
            if ($tipo === 'DNI') {
                $personaAdmin = PersonasAdmin::where('documento', $identificacion)->first();
            } elseif ($tipo === 'CUIL') {
                $personaAdmin = PersonasAdmin::where('cuil', $identificacion)->first();
            }

            if ($personaAdmin) {
                // Si existe en la base interna, retornamos de inmediato (SIN guardar en data_cache)
                $respuesta = [
                    'identificacion' => $identificacion,
                    'tipo' => $tipo,
                    'documento' => $personaAdmin->documento,
                    'nombre' => $personaAdmin->nombres,
                    'apellido' => $personaAdmin->apellidos,
                    'nombreCompleto' => $personaAdmin->nombreCompleto,
                ];
                return sendResponse($respuesta);
            }

            // 2. Resolver tipo_id en estados_generales
            $tipoEstado = EstadosGenerales::where('label', 'DOCUMENTO_TIPO')
                ->where('nombre', $tipo)
                ->first();

            $tipoId = $tipoEstado ? $tipoEstado->id : null;

            // 3. Buscar si existe una búsqueda externa previamente cacheada localmente
            $infractor = null;
            if ($tipoId) {
                $infractor = Infractor::where('tipo_id', $tipoId)
                    ->where(function ($q) use ($identificacion) {
                        $q->where('documento', $identificacion)
                          ->orWhere('identificacion', $identificacion);
                    })
                    ->first();
            }

            // 4. Verificar vigencia de la caché externa
            $cacheDias = (int) env('CACHE_PADRON_DIAS', 30);
            $fechaLimite = now()->subDays($cacheDias);

            $tieneCacheValida = $infractor 
                && $infractor->fecha_actualizacion 
                && $infractor->fecha_actualizacion->gt($fechaLimite)
                && !empty($infractor->data_cache);

            if ($tieneCacheValida) {
                return sendResponse($infractor->data_cache);
            }

            // 5. Si no está en PersonasAdmin ni tiene caché válida, invocar API externa
            $respuesta = null;
            try {
                if ($tipo === 'CUIT' && (str_starts_with($identificacion, '30') || str_starts_with($identificacion, '33') || str_starts_with($identificacion, '34'))) {
                    $respuesta = [
                        'identificacion' => $identificacion,
                        'tipo' => $tipo,
                        'nombre' => 'Empresa Simulada S.A.',
                        'apellido' => null,
                        'nombreCompleto' => 'Empresa Simulada S.A.',
                    ];
                } else {
                    // Intentar mediante el helper externo
                    $respuesta = consultar_persona_externo($identificacion, $tipo);
                }

                if (empty($respuesta)) {
                    throw new DomainException('No se encontró el infractor');
                }

                // 6. Almacenar o actualizar caché externa en infractores local
                if (!empty($respuesta) && $tipoId) {
                    $nombrePersona = $respuesta['nombreCompleto'] 
                        ?? ($respuesta['nombre'] ?? 'INFRACTOR ' . $identificacion);

                    $infractor = Infractor::updateOrCreate(
                        [
                            'tipo_id' => $tipoId,
                            'documento' => $respuesta['documento'] ?? $identificacion,
                        ],
                        [
                            'identificacion' => $identificacion,
                            'nombre' => $nombrePersona,
                            'data_cache' => $respuesta,
                            'fecha_actualizacion' => now(),
                        ]
                    );
                }

                return sendResponse($respuesta);

            } catch (Throwable $e) {
                // Fallback de resiliencia: si falla la API externa pero tenemos una caché local expirada
                if ($infractor && !empty($infractor->data_cache)) {
                    \Illuminate\Support\Facades\Log::warning("Fallo en consulta externa de infractor {$identificacion}, se retorna caché expirada: " . $e->getMessage());
                    return sendResponse($infractor->data_cache);
                }
                throw $e;
            }

        } catch (DomainException $e) {
            return sendResponse(null, ['general' => $e->getMessage()], 422);
        } catch (Throwable $e) {
            return error_response($e);
        }
    }
}
