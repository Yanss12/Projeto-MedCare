<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Paciente;

class PacienteController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response()->json(Paciente::all());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $paciente = Paciente::create($request->all());
        return response()->json($paciente, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $paciente = Paciente::findOrFail($id);
        return response()->json($paciente);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $paciente = Paciente::findOrFail($id);
        $paciente->update($request->all());
        return response()->json($paciente);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $paciente = Paciente::findOrFail($id);

        if ($paciente->agendamentos()->count() > 0 || $paciente->evolucoes()->count() > 0) {
            return response()->json(['error' => 'Não é possível excluir: Este paciente possui histórico clínico e não pode ser apagado do sistema.'], 400);
        }

        $paciente->delete();
        return response()->json(null, 204);
    }
}
