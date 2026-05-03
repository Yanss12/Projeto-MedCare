<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AgendamentoResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->uuid, // Substitui o ID interno pelo UUID público
            'paciente' => [
                'id' => $this->paciente->uuid ?? null,
                'nome' => $this->paciente->nome ?? null,
            ],
            'profissional' => [
                'id' => $this->profissional->uuid ?? null,
                'nome' => $this->profissional->nome ?? null,
                'especialidade' => $this->profissional->especialidade ?? null,
            ],
            'data_hora' => $this->data_hora,
            'status' => $this->status,
            'criado_em' => $this->created_at->toIso8601String(),
        ];
    }
}
