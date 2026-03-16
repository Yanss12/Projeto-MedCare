<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Agendamento extends Model
{
    // Campos permitidos para criação em massa
    protected $fillable = ['paciente_id', 'profissional_id', 'data_hora', 'status'];

    // Relacionamento: Todo agendamento pertence a um Paciente
    public function paciente()
    {
        return $this->belongsTo(Paciente::class);
    }

    // Relacionamento: Todo agendamento é com um Profissional
    public function profissional()
    {
        return $this->belongsTo(Profissional::class);
    }
}
