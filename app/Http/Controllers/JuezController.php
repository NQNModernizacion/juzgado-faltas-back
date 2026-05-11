<?php

namespace App\Http\Controllers;

use App\Models\Juez;
use App\Http\Requests\StoreJuezRequest;
use App\Http\Requests\UpdateJuezRequest;

class JuezController extends Controller
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
    public function store(StoreJuezRequest $request)
    {
        try {
        } catch (\Throwable $th) {
            return error_response($th, __FUNCTION__);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Juez $juez)
    {
        try {
        } catch (\Throwable $th) {
            return error_response($th, __FUNCTION__);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateJuezRequest $request, Juez $juez)
    {
        try {
        } catch (\Throwable $th) {
            return error_response($th, __FUNCTION__);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Juez $juez)
    {
        try {
        } catch (\Throwable $th) {
            return error_response($th, __FUNCTION__);
        }
    }
}
