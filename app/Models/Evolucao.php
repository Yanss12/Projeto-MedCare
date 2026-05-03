<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Evolucao extends Model
{
    use SoftDeletes, HasUuids;

    protected $table = 'evolucoes';

    public $incrementing  = false;
    protected $primaryKey = 'uuid';
    protected $keyType    = 'string';

    protected $fillable = [
        'paciente_id', 'profissional_id', 'data_hora', 'tipo_atendimento',
        'descricao_encrypted', 'sinais_vitais_encrypted', 'prescricao_encrypted'
    ];

    protected $casts = [
        'descricao_encrypted' => 'encrypted',
        'sinais_vitais_encrypted' => 'encrypted:array', // JSON array criptografado
        'prescricao_encrypted' => 'encrypted',
    ];

    protected $hidden = ['id', 'paciente_id', 'profissional_id'];

    public function paciente()
    {
        return $this->belongsTo(Paciente::class, 'paciente_id', 'id');
    }

    public function profissional()
    {
        return $this->belongsTo(Profissional::class, 'profissional_id', 'id');
    }
}
