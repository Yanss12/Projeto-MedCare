<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Evolucao;

class EvolucaoController extends Controller
{
    /**
     * Lista todas as evolucoes clinicas/registros de pacientes.
     */
    public function index()
    {
        return response()->json(Evolucao::all());
    }

    /**
     * Cria e salva uma nova anotacao clinica (evolucao).
     * Valida todos os campos antes de gravar - dado clinico e sensivel.
     */
    public function store(Request $request)
    {
        // Validacao dos campos - prontuarios sao dados sensiveis, nao aceitamos qualquer coisa
        $data = $request->validate([
            'paciente_id'      => 'required|integer|exists:pacientes,id',
            'profissional_id'  => 'nullable|integer|exists:profissionals,id',
            'profissional'     => 'nullable|string|max:255',
            'data_registro'    => 'required|date',
            'observacoes'      => 'required|string|max:10000',
            'prescricoes'      => 'nullable|array',
            'prescricoes.*'    => 'string|max:500',
        ]);

        $evolucao = Evolucao::create($data);
        return response()->json($evolucao, 201);
    }

    /**
     * Mostra os detalhes de uma evolucao especifica.
     */
    public function show(string $id)
    {
        $evolucao = Evolucao::findOrFail($id);
        return response()->json($evolucao);
    }

    /**
     * Atualiza os dados de uma evolucao existente.
     * Valida antes de atualizar.
     */
    public function update(Request $request, string $id)
    {
        $evolucao = Evolucao::findOrFail($id);

        $data = $request->validate([
            'paciente_id'      => 'sometimes|required|integer|exists:pacientes,id',
            'profissional_id'  => 'nullable|integer|exists:profissionals,id',
            'profissional'     => 'nullable|string|max:255',
            'data_registro'    => 'sometimes|required|date',
            'observacoes'      => 'sometimes|required|string|max:10000',
            'prescricoes'      => 'nullable|array',
            'prescricoes.*'    => 'string|max:500',
        ]);

        $evolucao->update($data);
        return response()->json($evolucao);
    }

    /**
     * Remove uma evolucao do sistema.
     */
    public function destroy(string $id)
    {
        $evolucao = Evolucao::findOrFail($id);
        $evolucao->delete();
        return response()->json(null, 204);
    }
}

