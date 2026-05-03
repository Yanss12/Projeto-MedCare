<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\PacienteRequest;
use App\Http\Resources\PacienteResource;
use App\Models\Paciente;
use Illuminate\Http\JsonResponse;

class PacienteController extends Controller
{
    public function index()
    {
        $pacientes = Paciente::all();
        
        return response()->json([
            'status' => 'success',
            'message' => 'Pacientes listados com sucesso.',
            'data' => PacienteResource::collection($pacientes)
        ]);
    }

    // Agora exige um PacienteRequest em vez de um Request genérico
    public function store(PacienteRequest $request): JsonResponse
    {
        // 1. Recebe apenas os dados pré-aprovados e limpos pelo PacienteRequest
        $validated = $request->validated();
        
        // 2. Mapeia para os nomes das colunas de segurança no banco
        $paciente = Paciente::create($this->mapData($validated));
        
        // 3. Devolve resposta padronizada sem expor a estrutura interna
        return response()->json([
            'status' => 'success',
            'message' => 'Paciente cadastrado com sucesso.',
            'data' => new PacienteResource($paciente),
        ], 201);
    }

    public function show(string $uuid): JsonResponse
    {
        // Busca sempre pelo UUID seguro, não pelo ID sequencial (1, 2, 3...)
        $paciente = Paciente::where('uuid', $uuid)->firstOrFail();
        
        return response()->json([
            'status' => 'success',
            'message' => 'Paciente carregado.',
            'data' => new PacienteResource($paciente),
        ]);
    }

    public function update(PacienteRequest $request, string $uuid): JsonResponse
    {
        $paciente = Paciente::where('uuid', $uuid)->firstOrFail();
        
        $paciente->update($this->mapData($request->validated()));
        
        return response()->json([
            'status' => 'success',
            'message' => 'Paciente atualizado com sucesso.',
            'data' => new PacienteResource($paciente),
        ]);
    }

    public function destroy(string $uuid): JsonResponse
    {
        $paciente = Paciente::where('uuid', $uuid)->firstOrFail();

        // Bloqueia a exclusão se houver vínculos
        if ($paciente->agendamentos()->count() > 0 || $paciente->evolucoes()->count() > 0) {
            return response()->json([
                'status' => 'error',
                'message' => 'Não é possível excluir: Este paciente possui histórico clínico e não pode ser apagado do sistema.',
                'data' => []
            ], 400); // 400 Bad Request
        }

        // Executa um Soft Delete silencioso (o model tem a trait)
        $paciente->delete();
        
        return response()->json([
            'status' => 'success',
            'message' => 'Paciente removido com sucesso.',
            'data' => []
        ]);
    }

    /**
     * Mapeia os dados da requisição (frontend) para os campos criptografados do banco (backend).
     */
    private function mapData(array $validated): array
    {
        return [
            'nome' => $validated['nome'],
            'data_nascimento' => $validated['data_nascimento'] ?? null,
            'necessitatransporte' => $validated['necessitatransporte'] ?? false,
            // O frontend manda "cpf", o backend salva em "cpf_encrypted"
            'cpf_encrypted' => $validated['cpf'] ?? null,
            'telefone_encrypted' => $validated['telefone'] ?? null,
            'endereco_encrypted' => $validated['endereco'] ?? null,
            'diagnostico_encrypted' => $validated['diagnostico'] ?? null,
            'alergias_encrypted' => $validated['alergias'] ?? null,
            'medicamentoscontrolados_encrypted' => $validated['medicamentoscontrolados'] ?? null,
        ];
    }
}
