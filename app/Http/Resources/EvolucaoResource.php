<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EvolucaoResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->uuid,
            'paciente' => [
                'id' => $this->paciente->uuid ?? null,
                'nome' => $this->paciente->nome ?? null,
            ],
            'profissional' => [
                'id' => $this->profissional->uuid ?? null,
                'nome' => $this->profissional->nome ?? null,
            ],
            'descricao' => $this->descricao_encrypted,
            'tipo_atendimento' => $this->tipo_atendimento,
            'sinais_vitais' => $this->sinais_vitais_encrypted,
            'prescricao' => $this->prescricao_encrypted,
            'data_hora' => $this->data_hora,
            'criado_em' => $this->created_at->toIso8601String(),
        ];
    }
}
