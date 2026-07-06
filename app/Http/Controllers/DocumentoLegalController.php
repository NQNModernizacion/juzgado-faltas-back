<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\StoreDocumentoLegalRequest;
use App\Models\Acta;
use App\Services\DocumentoLegalService;
use App\Models\DocumentoLegal;
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
            $documentos = DocumentoLegal::all();
            return sendResponse($documentos);
        } catch (Throwable $th) {
            return error_response($th);
        }
    }

    public function store(StoreDocumentoLegalRequest $request)
    {
        try {
            $documento = $this->service->crearDocumento($request->validated());
            return sendResponse($documento);
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

            // Retorna un stream directo en lugar de download para visualización en navegador (o download si prefieres)
            return $pdf->inline("documento_{$documento->id}.pdf");
        } catch (DomainException $e) {
            return sendResponse(null, ['general' => $e->getMessage()], 422);
        } catch (Throwable $th) {
            return error_response($th);
        }
    }

    public function show(string $id)
    {
        try {
            $documento = DocumentoLegal::findOrFail($id);
            return sendResponse($documento);
        } catch (Throwable $th) {
            return error_response($th);
        }
    }

    public function update(StoreDocumentoLegalRequest $request, string $id)
    {
        try {
            // Lógica basica de update
            $documento = DocumentoLegal::findOrFail($id);
            $documento->update($request->validated());
            return sendResponse($documento);
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
            return $pdf->inline("caratula_acta_{$acta->numero_acta}.pdf");
        } catch (Throwable $th) {
            return error_response($th);
        }
    }
}
