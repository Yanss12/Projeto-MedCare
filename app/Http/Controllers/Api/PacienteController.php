<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Paciente;
use App\Rules\ValidaCPF;

class PacienteController extends Controller
{
    /**
     * Retorna a lista completa de pacientes no banco.
     */
    public function index()
    {
        // Traz geral e devolve como JSON
        return response()->json(Paciente::all());
    }

    /**
     * Salva um novo paciente no banco de dados.
     * Valida todos os campos antes de persistir para garantir integridade dos dados.
     */
    public function store(Request $request)
    {
        // Valida estritamente os dados recebidos antes de qualquer gravacao
        $data = $request->validate([
            'nome'                   => 'required|string|max:255',
            'cpf'                    => ['nullable', 'string', new ValidaCPF, 'unique:pacientes,cpf'],
            'telefone'               => 'nullable|string|max:20',
            'endereco'               => 'nullable|string|max:500',
            'data_nascimento'        => 'nullable|date|before:today',
            'necessitatransporte'    => 'nullable|boolean',
            'diagnostico'            => 'nullable|string|max:2000',
            'alergias'               => 'nullable|array',
            'alergias.*'             => 'string|max:255',
            'medicamentoscontrolados'=> 'nullable|array',
            'medicamentoscontrolados.*' => 'string|max:255',
            'idade'                  => 'nullable|integer|min:0|max:150',
            'foto_url'               => 'nullable|string|max:500',
        ]);

        $paciente = Paciente::create($data);

        // Retorna o paciente criado com status 201 (Created)
        return response()->json($paciente, 201);
    }

    /**
     * Busca os dados de um paciente especifico pelo ID.
     */
    public function show(string $id)
    {
        // Se nao achar pelo ID, ele ja da erro 404 sozinho gracas ao findOrFail
        $paciente = Paciente::findOrFail($id);
        return response()->json($paciente);
    }

    /**
     * Atualiza os dados de um paciente existente.
     * Valida os campos antes de atualizar.
     */
    public function update(Request $request, string $id)
    {
        $paciente = Paciente::findOrFail($id);

        // Valida os dados recebidos (cpf pode ser o mesmo do proprio paciente)
        $data = $request->validate([
            'nome'                   => 'sometimes|required|string|max:255',
            'cpf'                    => ['nullable', 'string', new ValidaCPF, 'unique:pacientes,cpf,' . $id],
            'telefone'               => 'nullable|string|max:20',
            'endereco'               => 'nullable|string|max:500',
            'data_nascimento'        => 'nullable|date|before:today',
            'necessitatransporte'    => 'nullable|boolean',
            'diagnostico'            => 'nullable|string|max:2000',
            'alergias'               => 'nullable|array',
            'alergias.*'             => 'string|max:255',
            'medicamentoscontrolados'=> 'nullable|array',
            'medicamentoscontrolados.*' => 'string|max:255',
            'idade'                  => 'nullable|integer|min:0|max:150',
            'foto_url'               => 'nullable|string|max:500',
        ]);

        $paciente->update($data);

        return response()->json($paciente);
    }

    /**
     * Remove o paciente do banco (Deleta).
     */
    public function destroy(string $id)
    {
        $paciente = Paciente::findOrFail($id);

        // Bloqueio de seguranca: nao deixa apagar se ele tem historico clinico ou agendamentos
        if ($paciente->agendamentos()->count() > 0 || $paciente->evolucoes()->count() > 0) {
            return response()->json(['error' => 'Não é possível excluir: Este paciente possui histórico clínico e não pode ser apagado do sistema.'], 400);
        }

        // Se passou do bloqueio, pode apagar de verdade
        $paciente->delete();

        // Retorna status 204 (No Content), ou seja, deletado com sucesso
        return response()->json(null, 204);
    }
}

