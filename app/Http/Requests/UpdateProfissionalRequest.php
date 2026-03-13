<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProfissionalRequest extends FormRequest
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
        $profissionalId = $this->route('profissional') ? $this->route('profissional')->id : null;

        return [
            'nome' => ['required', 'string', 'max:255'],
            'especialidade' => ['required', 'string', 'max:255'],
            'crm' => ['required', 'string', 'max:50', 'unique:profissionais,crm,' . $profissionalId],
            'telefone' => ['nullable', 'string', 'max:20'],
            'email' => ['nullable', 'email', 'max:255'],
            'registro_interno' => ['nullable', 'string', 'max:50'],
            'horasvoluntarias' => ['nullable', 'integer', 'min:0'],
            'disponibilidade' => ['nullable', 'array'],
            'horarios' => ['nullable', 'string', 'max:255'],
            'status' => ['nullable', 'string', 'max:50'],
        ];
    }
}
