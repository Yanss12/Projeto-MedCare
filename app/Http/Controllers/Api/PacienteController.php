<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Paciente;

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
     */
    public function store(Request $request)
    {
        // Pega tudo que veio no body da requisição e cria o registro (confia no $fillable do model)
        $paciente = Paciente::create($request->all());
        
        // Retorna o paciente criado com status 201 (Created)
        return response()->json($paciente, 201);
    }

    /**
     * Busca os dados de um paciente específico pelo ID dele.
     */
    public function show(string $id)
    {
        // Se não achar pelo ID, ele já dá erro 404 sozinho graças ao findOrFail
        $paciente = Paciente::findOrFail($id);
        return response()->json($paciente);
    }

    /**
     * Atualiza os dados de um paciente existente.
     */
    public function update(Request $request, string $id)
    {
        $paciente = Paciente::findOrFail($id);
        
        // Aplica as mudanças vindas na requisição
        $paciente->update($request->all());
        
        return response()->json($paciente);
    }

    /**
     * Remove o paciente do banco (Deleta).
     */
    public function destroy(string $id)
    {
        $paciente = Paciente::findOrFail($id);

        // Bloqueio de segurança: não deixa apagar se ele tem histórico clínico ou agendamentos
        if ($paciente->agendamentos()->count() > 0 || $paciente->evolucoes()->count() > 0) {
            return response()->json(['error' => 'Não é possível excluir: Este paciente possui histórico clínico e não pode ser apagado do sistema.'], 400);
        }

        // Se passou do bloqueio, pode apagar de verdade
        $paciente->delete();
        
        // Retorna status 204 (No Content), ou seja, deletado com sucesso
        return response()->json(null, 204);
    }
}
