<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreEvolucaoRequest extends FormRequest
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
            'paciente_id'    => ['required', 'exists:pacientes,id'],
            'profissional_id'=> ['required', 'exists:profissionais,id'],
            'profissional'   => ['nullable', 'string', 'max:255'],
            'data_registro'  => ['required', 'date'],
            'observacoes'    => ['required', 'string'],
            'descricao'      => ['nullable', 'string'],
            'prescricoes'    => ['nullable', 'array'],
        ];
    }
}
