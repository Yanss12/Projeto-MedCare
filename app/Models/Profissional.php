<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Profissional extends Model
{
    use SoftDeletes, HasUuids;

    protected $table = 'profissionais';
    
    // Configuração do UUID como chave principal
    public $incrementing  = false;
    protected $primaryKey = 'uuid';
    protected $keyType    = 'string';

    protected $fillable = [
        'nome', 'especialidade', 'crm_encrypted', 'telefone_encrypted', 'email_encrypted',
        'registro_interno', 'horasvoluntarias', 'disponibilidade', 'horarios', 'status'
    ];

    protected $casts = [
        'crm_encrypted' => 'encrypted',
        'telefone_encrypted' => 'encrypted',
        'email_encrypted' => 'encrypted',
        'disponibilidade' => 'array',
    ];

    // Oculta a PK sequencial da saída da API
    protected $hidden = ['id'];

    public function agendamentos()
    {
        return $this->hasMany(Agendamento::class, 'profissional_id', 'id');
    }

    public function evolucoes()
    {
        return $this->hasMany(Evolucao::class, 'profissional_id', 'id');
    }
}
