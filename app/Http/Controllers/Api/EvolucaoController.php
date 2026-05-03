<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\EvolucaoRequest;
use App\Http\Resources\EvolucaoResource;
use App\Models\Evolucao;
use App\Models\Paciente;
use App\Models\Profissional;
use Illuminate\Http\JsonResponse;

class EvolucaoController extends Controller
{
    public function index()
    {
        $evolucoes = Evolucao::with(['paciente', 'profissional'])->get();
        
        return response()->json([
            'status' => 'success',
            'message' => 'Evoluções listadas com sucesso.',
            'data' => EvolucaoResource::collection($evolucoes)
        ]);
    }

    public function store(EvolucaoRequest $request): JsonResponse
    {
        $validated = $request->validated();
        
        // Tradução de UUID para ID interno
        $paciente = Paciente::where('uuid', $validated['paciente_uuid'])->firstOrFail();
        $profissional = Profissional::where('uuid', $validated['profissional_uuid'])->firstOrFail();

        $evolucao = Evolucao::create([
            'paciente_id' => $paciente->id,
            'profissional_id' => $profissional->id,
            'data_hora' => $validated['data_hora'],
            'tipo_atendimento' => $validated['tipo_atendimento'] ?? null,
            // Mapeando dados para as colunas criptografadas
            'descricao_encrypted' => $validated['descricao'],
            'sinais_vitais_encrypted' => $validated['sinais_vitais'] ?? null,
            'prescricao_encrypted' => $validated['prescricao'] ?? null,
        ]);
        
        $evolucao->load(['paciente', 'profissional']);
        
        return response()->json([
            'status' => 'success',
            'message' => 'Evolução registrada com sucesso.',
            'data' => new EvolucaoResource($evolucao),
        ], 201);
    }

    public function show(string $uuid): JsonResponse
    {
        $evolucao = Evolucao::with(['paciente', 'profissional'])
            ->where('uuid', $uuid)
            ->firstOrFail();
        
        return response()->json([
            'status' => 'success',
            'message' => 'Evolução carregada.',
            'data' => new EvolucaoResource($evolucao),
        ]);
    }

    public function update(EvolucaoRequest $request, string $uuid): JsonResponse
    {
        $evolucao = Evolucao::where('uuid', $uuid)->firstOrFail();
        $validated = $request->validated();
        
        $paciente = Paciente::where('uuid', $validated['paciente_uuid'])->firstOrFail();
        $profissional = Profissional::where('uuid', $validated['profissional_uuid'])->firstOrFail();

        $evolucao->update([
            'paciente_id' => $paciente->id,
            'profissional_id' => $profissional->id,
            'data_hora' => $validated['data_hora'],
            'tipo_atendimento' => $validated['tipo_atendimento'] ?? $evolucao->tipo_atendimento,
            'descricao_encrypted' => $validated['descricao'],
            'sinais_vitais_encrypted' => $validated['sinais_vitais'] ?? $evolucao->sinais_vitais_encrypted,
            'prescricao_encrypted' => $validated['prescricao'] ?? $evolucao->prescricao_encrypted,
        ]);
        
        $evolucao->load(['paciente', 'profissional']);
        
        return response()->json([
            'status' => 'success',
            'message' => 'Evolução atualizada com sucesso.',
            'data' => new EvolucaoResource($evolucao),
        ]);
    }

    public function destroy(string $uuid): JsonResponse
    {
        $evolucao = Evolucao::where('uuid', $uuid)->firstOrFail();
        
        $evolucao->delete();
        
        return response()->json([
            'status' => 'success',
            'message' => 'Evolução removida com sucesso.',
            'data' => []
        ]);
    }
}
