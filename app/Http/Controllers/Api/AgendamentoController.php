<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Agendamento;

class AgendamentoController extends Controller
{
    /**
     * Lista todos os agendamentos salvos no banco.
     */
    public function index()
    {
        return response()->json(Agendamento::all());
    }

    /**
     * Cria um novo agendamento.
     * Valida os dados e verifica existencia de paciente e profissional.
     */
    public function store(Request $request)
    {
        // Valida os dados: garante que o paciente e profissional existem no banco
        $data = $request->validate([
            'paciente_id'     => 'required|integer|exists:pacientes,id',
            'profissional_id' => 'required|integer|exists:profissionals,id',
            'data_hora'       => 'required|date|after:now',
            'status'          => 'required|string|in:Agendado,Confirmado,Cancelado,Realizado',
        ]);

        $agendamento = Agendamento::create($data);

        // Retorna o agendamento recem-criado com status de sucesso
        return response()->json($agendamento, 201);
    }

    /**
     * Traz as informacoes de um unico agendamento pelo ID.
     */
    public function show(string $id)
    {
        $agendamento = Agendamento::findOrFail($id);
        return response()->json($agendamento);
    }

    /**
     * Pega um agendamento existente e atualiza os dados dele.
     * Valida os dados antes de atualizar.
     */
    public function update(Request $request, string $id)
    {
        $agendamento = Agendamento::findOrFail($id);

        $data = $request->validate([
            'paciente_id'     => 'sometimes|required|integer|exists:pacientes,id',
            'profissional_id' => 'sometimes|required|integer|exists:profissionals,id',
            'data_hora'       => 'sometimes|required|date',
            'status'          => 'sometimes|required|string|in:Agendado,Confirmado,Cancelado,Realizado',
        ]);

        $agendamento->update($data);
        return response()->json($agendamento);
    }

    /**
     * Exclui o agendamento.
     */
    public function destroy(string $id)
    {
        $agendamento = Agendamento::findOrFail($id);
        $agendamento->delete();

        // Retorna status 204 indicando que deu certo e nao tem conteudo pra devolver
        return response()->json(null, 204);
    }
}

