<?php

namespace App\Http\Controllers;

use App\Models\Movimiento;
use App\Http\Requests\StoreMovimientoRequest;
use App\Http\Requests\UpdateMovimientoRequest;
use App\Services\MovimientoService;

class MovimientoController extends Controller
{
    public function __construct(
        protected MovimientoService $movimientoService
    ) {}

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
        } catch (\Throwable $th) {

            return error_response($th);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreMovimientoRequest $request)
    {
        try {
        } catch (\Throwable $th) {

            return error_response($th);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Movimiento $movimiento)
    {
        try {
        } catch (\Throwable $th) {

            return error_response($th);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function moverCausa(UpdateMovimientoRequest $request)
    {
        try {
            $movimiento = $this->movimientoService->moverCausa($request->validated());

            return sendResponse($movimiento);
        } catch (\DomainException $e) {
            return sendResponse(null, ['general' => $e->getMessage()], 422);
        } catch (\Throwable $th) {
            return error_response($th, __FUNCTION__);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Movimiento $movimiento)
    {
        try {
        } catch (\Throwable $th) {

            return error_response($th);
        }
    }
}
