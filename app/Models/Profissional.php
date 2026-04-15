<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Profissional extends Model
{
    protected $table = 'profissionais';
    protected $fillable = [
        'nome', 'especialidade', 'crm', 'telefone', 'email',
        'registro_interno', 'horasvoluntarias', 'disponibilidade', 'horarios', 'status'
    ];

    protected $casts = [
        'disponibilidade' => 'array',
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
