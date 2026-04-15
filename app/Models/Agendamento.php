<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Agendamento extends Model
{
    protected $fillable = ['paciente_id', 'profissional_id', 'data_hora', 'status'];

    public function paciente()
    {
        return $this->belongsTo(Paciente::class);
    }

    public function profissional()
    {
        return $this->belongsTo(Profissional::class);
    }
}
