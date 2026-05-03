<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AgendamentoRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            // O frontend deve enviar os UUIDs, não os IDs internos
            'paciente_uuid' => 'required|uuid|exists:pacientes,uuid',
            'profissional_uuid' => 'required|uuid|exists:profissionais,uuid',
            'data_hora' => 'required|date',
            'status' => 'string|in:agendado,confirmado,cancelado,concluido',
        ];
    }
}
