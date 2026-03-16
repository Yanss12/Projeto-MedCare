<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Evolucao;

class EvolucaoController extends Controller
{
    /**
     * Lista todas as evoluções clínicas/registros de pacientes.
     */
    public function index()
    {
        return response()->json(Evolucao::all());
    }

    /**
     * Cria e salva uma nova anotação clínica (evolução).
     */
    public function store(Request $request)
    {
        $evolucao = Evolucao::create($request->all());
        return response()->json($evolucao, 201);
    }

    /**
     * Mostra os detalhes de uma evolução específica.
     */
    public function show(string $id)
    {
        $evolucao = Evolucao::findOrFail($id);
        return response()->json($evolucao);
    }

    /**
     * Atualiza os dados de uma evolução existente.
     */
    public function update(Request $request, string $id)
    {
        $evolucao = Evolucao::findOrFail($id);
        $evolucao->update($request->all());
        return response()->json($evolucao);
    }

    /**
     * Remove uma evolução do sistema.
     */
    public function destroy(string $id)
    {
        $evolucao = Evolucao::findOrFail($id);
        $evolucao->delete();
        return response()->json(null, 204);
    }
}
