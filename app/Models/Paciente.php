<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Paciente extends Model
{
    protected $fillable = [
        'nome', 'cpf', 'telefone', 'data_nascimento',
        'endereco', 'necessitatransporte', 'diagnostico', 'alergias', 'medicamentoscontrolados', 'idade', 'foto_url'
    ];

    protected $casts = [
        'alergias' => 'array',
        'medicamentoscontrolados' => 'array',
        'necessitatransporte' => 'boolean',
    ];

    public function agendamentos()
    {
        return $this->hasMany(Agendamento::class);
    }

    public function evolucoes()
    {
        return $this->hasMany(Evolucao::class);
    }
}
