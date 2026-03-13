<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePacienteRequest extends FormRequest
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
            'nome' => ['required', 'string', 'max:255'],
            'cpf' => ['required', 'string', 'max:20', 'unique:pacientes,cpf'],
            'idade' => ['nullable', 'integer', 'min:0', 'max:150'],
            'telefone' => ['nullable', 'string', 'max:20'],
            'endereco' => ['nullable', 'string', 'max:255'],
            'necessitatransporte' => ['boolean'],
            'diagnostico' => ['nullable', 'string', 'max:1000'],
            'alergias' => ['nullable', 'array'],
            'medicamentoscontrolados' => ['nullable', 'array'],
        ];
    }
}
