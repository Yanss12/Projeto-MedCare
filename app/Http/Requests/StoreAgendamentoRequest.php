<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreAgendamentoRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'paciente_id' => ['required', 'exists:pacientes,id'],
            'profissional_id' => ['required', 'exists:profissionais,id'],
            'data_hora' => ['required', 'date'],
            'status' => ['required', 'string', 'in:aguardando,confirmado,concluida,cancelado']
        ];
    }
}
