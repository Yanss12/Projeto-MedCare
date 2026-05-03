<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\AgendamentoRequest;
use App\Http\Resources\AgendamentoResource;
use App\Models\Agendamento;
use App\Models\Paciente;
use App\Models\Profissional;
use Illuminate\Http\JsonResponse;

class AgendamentoController extends Controller
{
    public function index()
    {
        // Eager load para evitar o problema de N+1 queries
        $agendamentos = Agendamento::with(['paciente', 'profissional'])->get();
        
        return response()->json([
            'status' => 'success',
            'message' => 'Agendamentos listados com sucesso.',
            'data' => AgendamentoResource::collection($agendamentos)
        ]);
    }

    public function store(AgendamentoRequest $request): JsonResponse
    {
        $validated = $request->validated();
        
        // Tradução: UUIDs públicos (frontend) -> IDs internos (banco de dados)
        $paciente = Paciente::where('uuid', $validated['paciente_uuid'])->firstOrFail();
        $profissional = Profissional::where('uuid', $validated['profissional_uuid'])->firstOrFail();

        $agendamento = Agendamento::create([
            'paciente_id' => $paciente->id,
            'profissional_id' => $profissional->id,
            'data_hora' => $validated['data_hora'],
            'status' => $validated['status'] ?? 'agendado',
        ]);
        
        // Carrega as relações para o resource de retorno
        $agendamento->load(['paciente', 'profissional']);
        
        return response()->json([
            'status' => 'success',
            'message' => 'Agendamento criado com sucesso.',
            'data' => new AgendamentoResource($agendamento),
        ], 201);
    }

    public function show(string $uuid): JsonResponse
    {
        $agendamento = Agendamento::with(['paciente', 'profissional'])
            ->where('uuid', $uuid)
            ->firstOrFail();
        
        return response()->json([
            'status' => 'success',
            'message' => 'Agendamento carregado.',
            'data' => new AgendamentoResource($agendamento),
        ]);
    }

    public function update(AgendamentoRequest $request, string $uuid): JsonResponse
    {
        $agendamento = Agendamento::where('uuid', $uuid)->firstOrFail();
        $validated = $request->validated();
        
        $paciente = Paciente::where('uuid', $validated['paciente_uuid'])->firstOrFail();
        $profissional = Profissional::where('uuid', $validated['profissional_uuid'])->firstOrFail();

        $agendamento->update([
            'paciente_id' => $paciente->id,
            'profissional_id' => $profissional->id,
            'data_hora' => $validated['data_hora'],
            'status' => $validated['status'] ?? $agendamento->status,
        ]);
        
        $agendamento->load(['paciente', 'profissional']);
        
        return response()->json([
            'status' => 'success',
            'message' => 'Agendamento atualizado com sucesso.',
            'data' => new AgendamentoResource($agendamento),
        ]);
    }

    public function destroy(string $uuid): JsonResponse
    {
        $agendamento = Agendamento::where('uuid', $uuid)->firstOrFail();

        // Em vez de hard delete, usa soft delete
        $agendamento->delete();
        
        return response()->json([
            'status' => 'success',
            'message' => 'Agendamento removido com sucesso.',
            'data' => []
        ]);
    }
}
