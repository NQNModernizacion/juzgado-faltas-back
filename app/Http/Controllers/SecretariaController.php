<?php

namespace App\Http\Controllers;

use App\Models\Secretaria;
use App\Http\Requests\StoreSecretariaRequest;
use App\Http\Requests\UpdateSecretariaRequest;

class SecretariaController extends Controller
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
    public function store(StoreSecretariaRequest $request)
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
    public function show(Secretaria $secretaria)
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
    public function update(UpdateSecretariaRequest $request, Secretaria $secretaria)
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
    public function destroy(Secretaria $secretaria)
    {
        try {
        } catch (\Throwable $th) {
            saveLog($th, 'error', __FUNCTION__);

            return sendResponse(null, $th->getMessage(), 490);
        }
    }
}
