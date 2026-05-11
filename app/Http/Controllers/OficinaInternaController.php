<?php

namespace App\Http\Controllers;

use App\Models\OficinaInterna;
use App\Http\Requests\StoreOficinaInternaRequest;
use App\Http\Requests\UpdateOficinaInternaRequest;

class OficinaInternaController extends Controller
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
    public function store(StoreOficinaInternaRequest $request)
    {
        try {
        } catch (\Throwable $th) {
            return error_response($th, __FUNCTION__);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(OficinaInterna $oficinaInterna)
    {
        try {
        } catch (\Throwable $th) {
            return error_response($th, __FUNCTION__);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateOficinaInternaRequest $request, OficinaInterna $oficinaInterna)
    {
        try {
        } catch (\Throwable $th) {
            return error_response($th, __FUNCTION__);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(OficinaInterna $oficinaInterna)
    {
        try {
        } catch (\Throwable $th) {
            return error_response($th, __FUNCTION__);
        }
    }
}
