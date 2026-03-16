<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Profissional;

class ProfissionalController extends Controller
{
    /**
     * Retorna a lista de todos os profissionais cadastrados.
     */
    public function index()
    {
        return response()->json(Profissional::all());
    }

    /**
     * Cadastra um novo profissional no banco.
     */
    public function store(Request $request)
    {
        // Pega todos os dados enviados e cria direto
        $profissional = Profissional::create($request->all());
        
        return response()->json($profissional, 201); // 201 = Criado com sucesso
    }

    /**
     * Busca um único profissional pelo ID.
     */
    public function show(string $id)
    {
        $profissional = Profissional::findOrFail($id);
        return response()->json($profissional);
    }

    /**
     * Edita e atualiza os dados do profissional.
     */
    public function update(Request $request, string $id)
    {
        $profissional = Profissional::findOrFail($id);
        $profissional->update($request->all());
        return response()->json($profissional);
    }

    /**
     * Tenta deletar um profissional.
     */
    public function destroy(string $id)
    {
        $profissional = Profissional::findOrFail($id);

        // Bloqueio vital: se ele já atendeu alguém, não podemos apagar o histórico!
        // A orientação é inativar o profissional em vez de deletar fisicamente do banco.
        if ($profissional->agendamentos()->count() > 0 || $profissional->evolucoes()->count() > 0) {
            return response()->json(['error' => 'Não é possível excluir: Este profissional possui agendamentos ou histórico clínico. Por favor, edite o cadastro e mude o status para Inativo.'], 400);
        }

        // Se liberado, apaga mesmo
        $profissional->delete();
        
        return response()->json(null, 204);
    }
}
