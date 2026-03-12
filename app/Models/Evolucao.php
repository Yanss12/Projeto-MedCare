<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Evolucao extends Model
{
    protected $table = 'evolucoes';
    protected $fillable = [
        'paciente_id', 'profissional_id', 'profissional', 'data_registro', 'observacoes', 'prescricoes'
    ];

    protected $casts = [
        'prescricoes' => 'array',
    ];

    public function paciente()
    {
        return $this->belongsTo(Paciente::class);
    }

    public function profissional()
    {
        return $this->belongsTo(Profissional::class);
    }
}
