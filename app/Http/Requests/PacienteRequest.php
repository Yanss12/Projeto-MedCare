<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PacienteRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // A autorização é feita via Sanctum (middleware de rotas)
    }

    public function rules(): array
    {
        // Define exatamente os campos aceitos e suas regras de validação.
        // Se um hacker enviar "is_admin": true, será ignorado porque não está aqui.
        return [
            'nome' => 'required|string|max:255',
            'cpf' => 'nullable|string|max:14',
            'telefone' => 'nullable|string|max:20',
            'data_nascimento' => 'nullable|date',
            'endereco' => 'nullable|string',
            'necessitatransporte' => 'boolean',
            'diagnostico' => 'nullable|string',
            'alergias' => 'nullable|array',
            'medicamentoscontrolados' => 'nullable|array',
        ];
    }
}
