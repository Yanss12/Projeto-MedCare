<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Agendamento extends Model
{
    use SoftDeletes, HasUuids;

    public $incrementing  = false;
    protected $primaryKey = 'uuid';
    protected $keyType    = 'string';

    protected $fillable = ['paciente_id', 'profissional_id', 'data_hora', 'status'];

    // Ocultamos os IDs internos para evitar vazamentos de estrutura do banco
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
