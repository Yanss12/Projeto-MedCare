<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProfissionalRequest;
use App\Http\Resources\ProfissionalResource;
use App\Models\Profissional;
use Illuminate\Http\JsonResponse;

class ProfissionalController extends Controller
{
    public function index()
    {
        $profissionais = Profissional::all();
        
        return response()->json([
            'status' => 'success',
            'message' => 'Profissionais listados com sucesso.',
            'data' => ProfissionalResource::collection($profissionais)
        ]);
    }

    public function store(ProfissionalRequest $request): JsonResponse
    {
        $validated = $request->validated();
        
        $profissional = Profissional::create($this->mapData($validated));
        
        return response()->json([
            'status' => 'success',
            'message' => 'Profissional cadastrado com sucesso.',
            'data' => new ProfissionalResource($profissional),
        ], 201);
    }

    public function show(string $uuid): JsonResponse
    {
        $profissional = Profissional::where('uuid', $uuid)->firstOrFail();
        
        return response()->json([
            'status' => 'success',
            'message' => 'Profissional carregado.',
            'data' => new ProfissionalResource($profissional),
        ]);
    }

    public function update(ProfissionalRequest $request, string $uuid): JsonResponse
    {
        $profissional = Profissional::where('uuid', $uuid)->firstOrFail();
        
        $profissional->update($this->mapData($request->validated()));
        
        return response()->json([
            'status' => 'success',
            'message' => 'Profissional atualizado com sucesso.',
            'data' => new ProfissionalResource($profissional),
        ]);
    }

    public function destroy(string $uuid): JsonResponse
    {
        $profissional = Profissional::where('uuid', $uuid)->firstOrFail();

        // Evita exclusão se houver histórico clínico associado
        if ($profissional->agendamentos()->count() > 0 || $profissional->evolucoes()->count() > 0) {
            return response()->json([
                'status' => 'error',
                'message' => 'Não é possível excluir: Este profissional possui histórico e não pode ser apagado do sistema.',
                'data' => []
            ], 400);
        }

        $profissional->delete();
        
        return response()->json([
            'status' => 'success',
            'message' => 'Profissional removido com sucesso.',
            'data' => []
        ]);
    }

    private function mapData(array $validated): array
    {
        return [
            'nome' => $validated['nome'],
            'especialidade' => $validated['especialidade'],
            'crm_encrypted' => $validated['crm'] ?? null,
            'telefone_encrypted' => $validated['telefone'] ?? null,
            'email_encrypted' => $validated['email'] ?? null,
            'registro_interno' => $validated['registro_interno'] ?? null,
            'horasvoluntarias' => $validated['horasvoluntarias'] ?? 0,
            'disponibilidade' => $validated['disponibilidade'] ?? null,
            'horarios' => $validated['horarios'] ?? null,
            'status' => $validated['status'] ?? 'ativo',
        ];
    }
}
