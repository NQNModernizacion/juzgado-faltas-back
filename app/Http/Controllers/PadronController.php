<?php

namespace App\Http\Controllers;

use App\Models\Padron;
use App\Http\Requests\StorePadronRequest;
use App\Http\Requests\UpdatePadronRequest;

class PadronController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePadronRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Padron $padron)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePadronRequest $request, Padron $padron)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Padron $padron)
    {
        //
    }
}
