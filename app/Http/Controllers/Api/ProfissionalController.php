<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Profissional;

class ProfissionalController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response()->json(Profissional::all());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $profissional = Profissional::create($request->all());
        return response()->json($profissional, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $profissional = Profissional::findOrFail($id);
        return response()->json($profissional);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $profissional = Profissional::findOrFail($id);
        $profissional->update($request->all());
        return response()->json($profissional);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $profissional = Profissional::findOrFail($id);

        if ($profissional->agendamentos()->count() > 0 || $profissional->evolucoes()->count() > 0) {
            return response()->json(['error' => 'Não é possível excluir: Este profissional possui agendamentos ou histórico clínico. Por favor, edite o cadastro e mude o status para Inativo.'], 400);
        }

        $profissional->delete();
        return response()->json(null, 204);
    }
}
