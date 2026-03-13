<?php

namespace App\Http\Controllers;

use App\Models\Evolucao;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreEvolucaoRequest;
use App\Http\Requests\UpdateEvolucaoRequest;

class EvolucaoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreEvolucaoRequest $request)
    {
        Evolucao::create($request->validated());
        return redirect()->back();
    }

    /**
     * Display the specified resource.
     */
    public function show(Evolucao $evolucao)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Evolucao $evolucao)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateEvolucaoRequest $request, Evolucao $evolucao)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Evolucao $evolucao)
    {
        $evolucao->delete();
        return redirect()->back();
    }
}
