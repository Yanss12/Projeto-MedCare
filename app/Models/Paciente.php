<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Paciente extends Model
{
    // Campos que podem ser salvos no banco de uma vez só (mass assignment)
    protected $fillable = [
        'nome', 'cpf', 'telefone', 'data_nascimento',
        'endereco', 'necessitatransporte', 'diagnostico', 'alergias', 'medicamentoscontrolados', 'idade', 'foto_url'
    ];

    // Forçamos a conversão de alguns campos para tipos específicos quando vem do banco
    protected $casts = [
        'alergias' => 'array', // Salva e lê como array (embora no banco seja um JSON)
        'medicamentoscontrolados' => 'array', // Salva e lê como array
        'necessitatransporte' => 'boolean', // Converte para booleano (true/false)
    ];

    // Relacionamento: Um paciente pode ter vários agendamentos
    public function agendamentos()
    {
        return $this->hasMany(Agendamento::class);
    }

    // Relacionamento: Um paciente pode ter várias evoluções clínicas/anotações
    public function evolucoes()
    {
        return $this->hasMany(Evolucao::class);
    }
}
