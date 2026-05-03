<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PacienteResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        // Aqui definimos exatamente como o JSON vai chegar no frontend.
        // O ID interno NUNCA é exposto.
        return [
            'id' => $this->uuid, // Enviamos o UUID chamando-o de 'id' publicamente
            'nome' => $this->nome,
            'cpf' => $this->cpf_encrypted,
            'telefone' => $this->telefone_encrypted,
            'data_nascimento' => $this->data_nascimento ? $this->data_nascimento->format('Y-m-d') : null,
            'endereco' => $this->endereco_encrypted,
            'necessitatransporte' => $this->necessitatransporte,
            'diagnostico' => $this->diagnostico_encrypted,
            'alergias' => $this->alergias_encrypted,
            'medicamentoscontrolados' => $this->medicamentoscontrolados_encrypted,
            'criado_em' => $this->created_at->toIso8601String(),
        ];
    }
}
