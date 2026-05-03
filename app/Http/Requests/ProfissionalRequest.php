<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProfissionalRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'nome' => 'required|string|max:255',
            'especialidade' => 'required|string|max:255',
            'crm' => 'nullable|string|max:20',
            'telefone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'registro_interno' => 'nullable|string|max:50',
            'horasvoluntarias' => 'integer|min:0',
            'disponibilidade' => 'nullable|array',
            'horarios' => 'nullable|string',
            'status' => 'string|in:ativo,inativo,ferias',
        ];
    }
}
