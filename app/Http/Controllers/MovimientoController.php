<?php

namespace App\Http\Controllers;

use App\Models\Movimiento;
use App\Http\Requests\StoreMovimientoRequest;
use App\Http\Requests\UpdateMovimientoRequest;

class MovimientoController extends Controller
{
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
    public function update(UpdateMovimientoRequest $request, Movimiento $movimiento)
    {
        try {
        } catch (\Throwable $th) {
            
           return error_response($th);
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
