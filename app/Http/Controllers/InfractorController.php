<?php

namespace App\Http\Controllers;

use App\Http\Requests\ConsultarInfractorRequest;
use App\Models\Infractor;
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

            // Datos mock/simulados para los tipos que el usuario armará después
            $data = [];

            // Simulación de búsqueda de persona física o jurídica
            // if (in_array($tipo, ['DNI', 'CUIL', 'CUIT', 'CI', 'LC', 'LE', 'PAS', 'CF', 'OT', 'EXT'])) {
            $respuesta = null;
            if (in_array($tipo, ['DNI', 'CUIL', 'CUIT'])) {

                if ($tipo === 'DNI') {
                    $data = PersonasAdmin::where('documento', $identificacion)->first();
                    $respuesta = [
                        'identificacion' => $identificacion,
                        'tipo' => $tipo,
                        'documento' => $data->documento,
                        'nombre' => $data->nombres,
                        'apellido' => $data->apellidos,
                        'nombreCompleto' => $data->nombreCompleto,
                        // 'fecha_nacimiento' => '1990-01-01',
                        // 'sexo' => 'M',
                        // 'nacionalidad' => 'Argentina',
                        // 'direccion' => 'Av. Siempre Viva 742',
                        // 'email' => 'juan.perez@example.com',
                        // 'telefono' => '2994123456',
                        // 'simulado' => true
                    ];
                } else if ($tipo === 'CUIL') {
                    $data = PersonasAdmin::where('cuil', $identificacion)->first();
                    $respuesta = [
                        'identificacion' => $identificacion,
                        'tipo' => $tipo,
                        'documento' => $data->documento,
                        'nombre' => $data->nombres,
                        'apellido' => $data->apellidos,
                        'nombreCompleto' => $data->nombreCompleto,
                        // 'fecha_nacimiento' => '1990-01-01',
                        // 'sexo' => 'M',
                        // 'nacionalidad' => 'Argentina',
                        // 'direccion' => 'Av. Siempre Viva 742',
                        // 'email' => 'juan.perez@example.com',
                        // 'telefono' => '2994123456',
                        // 'simulado' => true
                    ];
                } elseif ($tipo === 'CUIT') {
                    // Si es un CUIT de empresa (empieza con 30, 33 o 34), devolvemos datos de persona jurídica
                    if ($tipo === 'CUIT' && (str_starts_with($identificacion, '30') || str_starts_with($identificacion, '33') || str_starts_with($identificacion, '34'))) {
                        $data['razon_social'] = 'Empresa Simulada S.A.';
                        $data['nombre'] = 'Empresa Simulada S.A.';
                        unset($data['apellido'], $data['fecha_nacimiento'], $data['sexo']);
                    }
                    $respuesta = [
                        'identificacion' => $identificacion,
                        'tipo' => $tipo,
                        'nombre' => $data['nombre'],
                        'apellido' => null,
                        'nombreCompleto' => $data['razon_social'],
                        // 'fecha_nacimiento' => '1990-01-01',
                        // 'sexo' => 'M',
                        // 'nacionalidad' => 'Argentina',
                        // 'direccion' => 'Av. Siempre Viva 742',
                        // 'email' => 'juan.perez@example.com',
                        // 'telefono' => '2994123456',
                        // 'simulado' => true
                    ];
                }

                if (is_null($respuesta)) {
                    throw new DomainException('No se encontro el infractor');
                }
            }

            return sendResponse($respuesta);
        } catch (DomainException $e) {
            return sendResponse(null, ['general' => $e->getMessage()], 422);
        } catch (Throwable $e) {
            return error_response($e);
        }
    }
}
