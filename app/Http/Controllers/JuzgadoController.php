<?php

namespace App\Http\Controllers;

use App\Models\Juzgado;
use App\Http\Requests\StoreJuzgadoRequest;
use App\Http\Requests\UpdateJuzgadoRequest;

class JuzgadoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
        } catch (\Throwable $th) {
            saveLog($th, 'error', __FUNCTION__);

            return sendResponse(null, $th->getMessage(), 490);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreJuzgadoRequest $request)
    {
        try {
        } catch (\Throwable $th) {
            saveLog($th, 'error', __FUNCTION__);

            return sendResponse(null, $th->getMessage(), 490);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Juzgado $juzgado)
    {
        try {
        } catch (\Throwable $th) {
            saveLog($th, 'error', __FUNCTION__);

            return sendResponse(null, $th->getMessage(), 490);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateJuzgadoRequest $request, Juzgado $juzgado)
    {
        try {
        } catch (\Throwable $th) {
            saveLog($th, 'error', __FUNCTION__);

            return sendResponse(null, $th->getMessage(), 490);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Juzgado $juzgado)
    {
        try {
        } catch (\Throwable $th) {
            saveLog($th, 'error', __FUNCTION__);

            return sendResponse(null, $th->getMessage(), 490);
        }
    }
}
