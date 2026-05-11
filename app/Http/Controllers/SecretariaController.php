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
            return error_response($th, __FUNCTION__);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreSecretariaRequest $request)
    {
        try {
        } catch (\Throwable $th) {
            return error_response($th, __FUNCTION__);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Secretaria $secretaria)
    {
        try {
        } catch (\Throwable $th) {
            return error_response($th, __FUNCTION__);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateSecretariaRequest $request, Secretaria $secretaria)
    {
        try {
        } catch (\Throwable $th) {
            return error_response($th, __FUNCTION__);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Secretaria $secretaria)
    {
        try {
        } catch (\Throwable $th) {
            return error_response($th, __FUNCTION__);
        }
    }
}
