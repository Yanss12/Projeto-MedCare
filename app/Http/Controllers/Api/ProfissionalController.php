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
     * Valida todos os campos antes de gravar.
     */
    public function store(Request $request)
    {
        // Validacao rigorosa dos dados recebidos
        $data = $request->validate([
            'nome'               => 'required|string|max:255',
            'especialidade'      => 'required|string|max:255',
            'crm'                => 'required|string|max:30|unique:profissionals,crm',
            'registro_interno'   => 'nullable|string|max:100',
            'telefone'           => 'nullable|string|max:20',
            'email'              => 'nullable|email|max:255',
            'horasvoluntarias'   => 'nullable|numeric|min:0',
            'disponibilidade'    => 'nullable|array',
            'disponibilidade.*'  => 'string|max:50',
            'horarios'           => 'nullable|string|max:500',
            'status'             => 'nullable|string|in:Ativo,Inativo,Férias,Licença',
            'foto_url'           => 'nullable|string|max:500',
        ]);

        $profissional = Profissional::create($data);

        return response()->json($profissional, 201); // 201 = Criado com sucesso
    }

    /**
     * Busca um unico profissional pelo ID.
     */
    public function show(string $id)
    {
        $profissional = Profissional::findOrFail($id);
        return response()->json($profissional);
    }

    /**
     * Edita e atualiza os dados do profissional.
     * Valida os campos antes de atualizar.
     */
    public function update(Request $request, string $id)
    {
        $profissional = Profissional::findOrFail($id);

        // CRM pode ser o mesmo do proprio profissional, por isso ignoramos o ID dele na unicidade
        $data = $request->validate([
            'nome'               => 'sometimes|required|string|max:255',
            'especialidade'      => 'sometimes|required|string|max:255',
            'crm'                => 'sometimes|required|string|max:30|unique:profissionals,crm,' . $id,
            'registro_interno'   => 'nullable|string|max:100',
            'telefone'           => 'nullable|string|max:20',
            'email'              => 'nullable|email|max:255',
            'horasvoluntarias'   => 'nullable|numeric|min:0',
            'disponibilidade'    => 'nullable|array',
            'disponibilidade.*'  => 'string|max:50',
            'horarios'           => 'nullable|string|max:500',
            'status'             => 'nullable|string|in:Ativo,Inativo,Férias,Licença',
            'foto_url'           => 'nullable|string|max:500',
        ]);

        $profissional->update($data);
        return response()->json($profissional);
    }

    /**
     * Tenta deletar um profissional.
     */
    public function destroy(string $id)
    {
        $profissional = Profissional::findOrFail($id);

        // Bloqueio vital: se ele ja atendeu alguem, nao podemos apagar o historico!
        if ($profissional->agendamentos()->count() > 0 || $profissional->evolucoes()->count() > 0) {
            return response()->json(['error' => 'Não é possível excluir: Este profissional possui agendamentos ou histórico clínico. Por favor, edite o cadastro e mude o status para Inativo.'], 400);
        }

        $profissional->delete();

        return response()->json(null, 204);
    }
}

