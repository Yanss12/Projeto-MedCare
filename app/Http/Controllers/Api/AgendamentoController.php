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
     */
    public function store(Request $request)
    {
        $agendamento = Agendamento::create($request->all());
        
        // Retorna o agendamento recém-criado com status de sucesso
        return response()->json($agendamento, 201);
    }

    /**
     * Traz as informações de um único agendamento pelo ID.
     */
    public function show(string $id)
    {
        $agendamento = Agendamento::findOrFail($id);
        return response()->json($agendamento);
    }

    /**
     * Pega um agendamento existente e atualiza os dados dele.
     */
    public function update(Request $request, string $id)
    {
        $agendamento = Agendamento::findOrFail($id);
        $agendamento->update($request->all());
        return response()->json($agendamento);
    }

    /**
     * Exclui o agendamento.
     */
    public function destroy(string $id)
    {
        $agendamento = Agendamento::findOrFail($id);
        $agendamento->delete();
        
        // Retorna status 204 indicando que deu certo e não tem conteúdo pra devolver
        return response()->json(null, 204);
    }
}
