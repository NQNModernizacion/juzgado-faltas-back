<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\StoreDocumentoLegalRequest;
use App\Http\Resources\DocumentoLegalResource;
use App\Models\Acta;
use App\Services\DocumentoLegalService;
use App\Models\DocumentoLegal;
use App\Models\PlantillaDocumento;
use DomainException;
use Throwable;

class DocumentoLegalController extends Controller
{
    protected $service;

    public function __construct(DocumentoLegalService $service)
    {
        $this->service = $service;
    }

    public function index()
    {
        try {
            $documentos = DocumentoLegal::with('plantilla')->get();
            return sendResponse(DocumentoLegalResource::collection($documentos));
        } catch (Throwable $th) {
            return error_response($th);
        }
    }

    /**
     * Obtiene todos los documentos/formularios legales cargados para un acta.
     */
    public function getByActa(string $actaId)
    {
        try {
            $acta = Acta::findOrFail($actaId);
            $documentos = DocumentoLegal::with('plantilla')
                ->where('acta_id', $acta->id)
                ->orderBy('created_at', 'desc')
                ->get();

            return sendResponse(DocumentoLegalResource::collection($documentos));
        } catch (DomainException $e) {
            return sendResponse(null, ['general' => $e->getMessage()], 422);
        } catch (Throwable $th) {
            return error_response($th);
        }
    }

    /**
     * Precarga la plantilla con los datos del acta para el editor WYSIWYG.
     */
    public function precargar(string $actaId, string $plantillaId)
    {
        try {
            $acta = Acta::findOrFail($actaId);
            $plantilla = is_numeric($plantillaId)
                ? PlantillaDocumento::findOrFail($plantillaId)
                : PlantillaDocumento::where('codigo', $plantillaId)->firstOrFail();

            $html = $this->service->generarHtmlPrecarga($acta, $plantilla);

            return sendResponse([
                'acta_id' => $acta->id,
                'plantilla_id' => $plantilla->id,
                'plantilla_codigo' => $plantilla->codigo,
                'plantilla_nombre' => $plantilla->nombre,
                'tipo' => $plantilla->codigo,
                'contenido_html' => $html,
            ]);
        } catch (DomainException $e) {
            return sendResponse(null, ['general' => $e->getMessage()], 422);
        } catch (Throwable $th) {
            return error_response($th);
        }
    }

    public function store(StoreDocumentoLegalRequest $request)
    {
        try {
            $documento = $this->service->crearDocumento($request->validated());
            $documento->load('plantilla');
            return sendResponse(new DocumentoLegalResource($documento));
        } catch (DomainException $e) {
            return sendResponse(null, ['general' => $e->getMessage()], 422);
        } catch (Throwable $th) {
            return error_response($th);
        }
    }

    public function generarPdf($id)
    {
        try {
            $documento = DocumentoLegal::findOrFail($id);
            $pdf = $this->service->generarPdfSpatie($documento);

            $base64 = $pdf->base64();
            $size = (int) (strlen($base64) * 3 / 4) - (substr($base64, -2) === '==' ? 2 : (substr($base64, -1) === '=' ? 1 : 0));

            return sendResponse([
                'type' => 'pdf',
                'file_name' => "documento_{$documento->id}.pdf",
                'size' => $size,
                'file' => 'data:application/pdf;base64,' . $base64
            ]);
        } catch (DomainException $e) {
            return sendResponse(null, ['general' => $e->getMessage()], 422);
        } catch (Throwable $th) {
            return error_response($th);
        }
    }

    public function show(string $id)
    {
        try {
            $documento = DocumentoLegal::with('plantilla')->findOrFail($id);
            return sendResponse(new DocumentoLegalResource($documento));
        } catch (Throwable $th) {
            return error_response($th);
        }
    }

    public function update(StoreDocumentoLegalRequest $request, string $id)
    {
        try {
            $documento = DocumentoLegal::findOrFail($id);
            $documento->update($request->validated());
            $documento->load('plantilla');
            return sendResponse(new DocumentoLegalResource($documento));
        } catch (DomainException $e) {
            return sendResponse(null, ['general' => $e->getMessage()], 422);
        } catch (Throwable $th) {
            return error_response($th);
        }
    }

    public function destroy(string $id)
    {
        try {
            $documento = DocumentoLegal::findOrFail($id);
            $documento->delete();
            return sendResponse(['message' => 'Eliminado con éxito']);
        } catch (Throwable $th) {
            return error_response($th);
        }
    }

    public function generarCaratula(Acta $acta)
    {
        try {
            $pdf = $this->service->crearCaratula($acta);

            $base64 = $pdf->base64();
            $size = (int) (strlen($base64) * 3 / 4) - (substr($base64, -2) === '==' ? 2 : (substr($base64, -1) === '=' ? 1 : 0));

            return sendResponse([
                'type' => 'pdf',
                'file_name' => "caratula_acta_{$acta->numero_acta}.pdf",
                'size' => $size,
                'file' => 'data:application/pdf;base64,' . $base64
            ]);
        } catch (Throwable $th) {
            return error_response($th);
        }
    }
}
