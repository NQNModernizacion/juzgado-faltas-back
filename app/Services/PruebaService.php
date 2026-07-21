<?php

namespace App\Services;

use App\Models\Prueba;
use App\Models\Archivo;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use DomainException;

class PruebaService
{
    /**
     * Registra una nueva prueba asociada a un acta.
     *
     * @param array $data
     * @param int $userId
     * @return Prueba
     * @throws DomainException
     */
    public function registrarPrueba(array $data, int $userId): Prueba
    {
        return DB::transaction(function () use ($data, $userId) {
            // 1. Crear el registro de la prueba
            $prueba = Prueba::create([
                'acta_id' => $data['acta_id'],
                'tipo_archivo' => $data['tipo_archivo'],
                'observacion' => $data['observacion'] ?? null,
                'user_id' => $userId,
            ]);

            // 2. Si viene archivo, guardarlo físicamente y en base de datos
            if (isset($data['archivo']) && $data['archivo']->isValid()) {
                $archivo = $data['archivo'];
                $path = storage_file($archivo, "pruebas/{$data['acta_id']}");

                if ($path === null) {
                    throw new DomainException("Error al guardar el archivo físico en el servidor.");
                }

                $prueba->archivo()->create([
                    'path_archivo' => $path,
                    'nombre_original' => $archivo->getClientOriginalName(),
                    'extension' => $archivo->getClientOriginalExtension() ?: $archivo->guessExtension() ?: 'bin',
                    'size' => $archivo->getSize(),
                    'user_id' => $userId,
                ]);
            }

            return $prueba->load(['archivo', 'user']);
        });
    }

    /**
     * Actualiza una prueba existente.
     *
     * @param int $id
     * @param array $data
     * @param int $userId
     * @return Prueba
     * @throws DomainException
     */
    public function actualizarPrueba(int $id, array $data, int $userId): Prueba
    {
        return DB::transaction(function () use ($id, $data, $userId) {
            $prueba = Prueba::findOrFail($id);

            $removerArchivo = isset($data['remover_archivo']) && filter_var($data['remover_archivo'], FILTER_VALIDATE_BOOLEAN);
            $tieneNuevoArchivo = isset($data['archivo']) && $data['archivo']->isValid();
            $cambiaATexto = isset($data['tipo_archivo']) && !$data['tipo_archivo'];

            // 1. Remover archivo viejo si se solicita remover, si se sube uno nuevo o si el tipo cambia a texto
            if ($removerArchivo || $tieneNuevoArchivo || $cambiaATexto) {
                $archivoViejo = $prueba->archivo;
                if ($archivoViejo) {
                    // Borrar físicamente de disk
                    Storage::disk('serverdata')->delete($archivoViejo->path_archivo);
                    // Borrar lógicamente el registro de la tabla archivos
                    $archivoViejo->delete();
                }
            }

            // 2. Subir nuevo archivo si existe
            if ($tieneNuevoArchivo) {
                $archivo = $data['archivo'];
                $path = storage_file($archivo, "pruebas/{$prueba->acta_id}");

                if ($path === null) {
                    throw new DomainException("Error al guardar el nuevo archivo físico en el servidor.");
                }

                $prueba->archivo()->create([
                    'path_archivo' => $path,
                    'nombre_original' => $archivo->getClientOriginalName(),
                    'extension' => $archivo->getClientOriginalExtension() ?: $archivo->guessExtension() ?: 'bin',
                    'size' => $archivo->getSize(),
                    'user_id' => $userId,
                ]);
            }

            // 3. Actualizar campos del modelo de Prueba
            if (isset($data['tipo_archivo'])) {
                $prueba->tipo_archivo = $data['tipo_archivo'];
            }
            if (array_key_exists('observacion', $data)) {
                $prueba->observacion = $data['observacion'];
            }

            $prueba->save();

            return $prueba->load(['archivo', 'user']);
        });
    }

    /**
     * Elimina lógicamente una prueba.
     *
     * @param int $id
     * @return bool
     */
    public function eliminarPrueba(int $id): bool
    {
        return DB::transaction(function () use ($id) {
            $prueba = Prueba::findOrFail($id);

            // Eliminar lógicamente el archivo polimórfico si existe
            $archivo = $prueba->archivo;
            if ($archivo) {
                $archivo->delete();
            }

            // Eliminar lógicamente la prueba
            return $prueba->delete();
        });
    }

    /**
     * Lista todas las pruebas asociadas a un acta.
     *
     * @param int $actaId
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function listarPruebasPorActa(int $actaId)
    {
        return Prueba::with(['archivo', 'user'])
            ->where('acta_id', $actaId)
            ->get();
    }

    /**
     * Obtiene el detalle de la prueba y lee el archivo binario.
     *
     * @param int $id
     * @return Prueba
     */
    public function obtenerDetalleConArchivo(int $id): Prueba
    {
        return Prueba::with(['archivo', 'user'])->findOrFail($id);
    }
}
