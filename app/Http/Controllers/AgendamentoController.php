<?php

namespace App\Http\Controllers;

use App\Models\Agendamento;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreAgendamentoRequest;
use App\Http\Requests\UpdateAgendamentoRequest;

class AgendamentoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $agendamentos = Agendamento::all();
        $pacientes = \App\Models\Paciente::orderBy('nome')->get();
        $profissionais = \App\Models\Profissional::orderBy('nome')->get();

        return \Inertia\Inertia::render('Agendamentos', [
            'agendamentos' => $agendamentos,
            'pacientes' => $pacientes,
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
    public function store(StoreAgendamentoRequest $request)
    {
        Agendamento::create($request->validated());
        return redirect()->back();
    }

    /**
     * Display the specified resource.
     */
    public function show(Agendamento $agendamento)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Agendamento $agendamento)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateAgendamentoRequest $request, Agendamento $agendamento)
    {
        $agendamento->update($request->validated());
        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Agendamento $agendamento)
    {
        $agendamento->delete();
        return redirect()->back();
    }
}
