<?php

namespace App\Http\Controllers;

use App\Models\Profissional;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreProfissionalRequest;
use App\Http\Requests\UpdateProfissionalRequest;

class ProfissionalController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $profissionais = Profissional::orderBy('nome')->get();
        return \Inertia\Inertia::render('Profissionais', [
            'profissionais' => $profissionais
        ]);
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
    public function store(StoreProfissionalRequest $request)
    {
        Profissional::create($request->validated());
        return redirect()->back();
    }

    /**
     * Display the specified resource.
     */
    public function show(Profissional $profissional)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Profissional $profissional)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateProfissionalRequest $request, Profissional $profissional)
    {
        $profissional->update($request->validated());
        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Profissional $profissional)
    {
        $profissional->delete();
        return redirect()->back();
    }
}
