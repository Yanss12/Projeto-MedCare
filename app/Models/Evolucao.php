<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Evolucao extends Model
{
    // Força o nome da tabela no plural corretamente
    protected $table = 'evolucoes';
    
    // Lista de colunas que podem ser salvas de uma vez
    protected $fillable = [
        'paciente_id', 'profissional_id', 'profissional', 'data_registro', 'observacoes', 'prescricoes'
    ];

    // Converte de JSON (no banco) para array (no PHP) quando lermos o campo "prescricoes"
    protected $casts = [
        'prescricoes' => 'array',
    ];

    // Relacionamento: Toda evolução clínica pertence a um Paciente
    public function paciente()
    {
        return $this->belongsTo(Paciente::class);
    }

    // Relacionamento: Toda evolução foi feita por um Profissional
    public function profissional()
    {
        return $this->belongsTo(Profissional::class);
    }
}
