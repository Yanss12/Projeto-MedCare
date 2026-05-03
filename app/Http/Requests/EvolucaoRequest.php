<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EvolucaoRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'paciente_uuid' => 'required|uuid|exists:pacientes,uuid',
            'profissional_uuid' => 'required|uuid|exists:profissionais,uuid',
            'descricao' => 'required|string',
            'data_hora' => 'required|date',
            'tipo_atendimento' => 'nullable|string|max:100',
            'sinais_vitais' => 'nullable|array',
            'prescricao' => 'nullable|string',
        ];
    }
}
