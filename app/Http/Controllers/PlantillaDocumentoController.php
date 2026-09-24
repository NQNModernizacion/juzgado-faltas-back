<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PlantillaDocumento;
use App\Http\Requests\StorePlantillaDocumentoRequest;
use App\Http\Resources\PlantillaDocumentoResource;
use App\Models\Acta;
use Throwable;
use DomainException;

class PlantillaDocumentoController extends Controller
{
    public function index()
    {
        try {
            $plantillas = PlantillaDocumento::all();
            return sendResponse(PlantillaDocumentoResource::collection($plantillas));
        } catch (Throwable $th) {
            return error_response($th);
        }
    }

    public function store(StorePlantillaDocumentoRequest $request)
    {
        try {
            $plantilla = PlantillaDocumento::create($request->validated());
            return sendResponse(new PlantillaDocumentoResource($plantilla));
        } catch (Throwable $th) {
            return error_response($th);
        }
    }

    public function show(string $id)
    {
        try {
            $plantilla = PlantillaDocumento::findOrFail($id);
            return sendResponse(new PlantillaDocumentoResource($plantilla));
        } catch (Throwable $th) {
            return error_response($th);
        }
    }

    public function update(StorePlantillaDocumentoRequest $request, string $id)
    {
        try {
            $plantilla = PlantillaDocumento::findOrFail($id);
            $plantilla->update($request->validated());
            return sendResponse(new PlantillaDocumentoResource($plantilla));
        } catch (Throwable $th) {
            return error_response($th);
        }
    }

    public function destroy(string $id)
    {
        try {
            $plantilla = PlantillaDocumento::findOrFail($id);
            $plantilla->delete();
            return sendResponse(['message' => 'Plantilla eliminada con éxito']);
        } catch (Throwable $th) {
            return error_response($th);
        }
    }
}
