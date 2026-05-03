<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProfissionalResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->uuid, // Substitui o ID interno pelo UUID público
            'nome' => $this->nome,
            'especialidade' => $this->especialidade,
            'crm' => $this->crm_encrypted,
            'telefone' => $this->telefone_encrypted,
            'email' => $this->email_encrypted,
            'registro_interno' => $this->registro_interno,
            'horasvoluntarias' => $this->horasvoluntarias,
            'disponibilidade' => $this->disponibilidade,
            'horarios' => $this->horarios,
            'status' => $this->status,
            'criado_em' => $this->created_at->toIso8601String(),
        ];
    }
}
