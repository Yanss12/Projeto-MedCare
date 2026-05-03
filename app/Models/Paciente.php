<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Paciente extends Model
{
    use SoftDeletes, HasUuids;

    // Define UUID como chave primária na aplicação (API), enquanto o banco mantém 'id' otimizado
    public $incrementing  = false;
    protected $primaryKey = 'uuid';
    protected $keyType    = 'string';

    // Mass Assignment explícito
    protected $fillable = [
        'nome', 'data_nascimento', 'necessitatransporte',
        'cpf_encrypted', 'telefone_encrypted', 'endereco_encrypted', 
        'diagnostico_encrypted', 'alergias_encrypted', 'medicamentoscontrolados_encrypted'
    ];

    // Criptografia transparente: o Laravel criptografa ao salvar e descriptografa ao ler
    protected $casts = [
        'cpf_encrypted' => 'encrypted',
        'telefone_encrypted' => 'encrypted',
        'endereco_encrypted' => 'encrypted',
        'diagnostico_encrypted' => 'encrypted',
        'alergias_encrypted' => 'encrypted:array', // Encripta arrays JSON automaticamente
        'medicamentoscontrolados_encrypted' => 'encrypted:array',
        'necessitatransporte' => 'boolean',
        'data_nascimento' => 'date',
    ];

    // Oculta o ID sequencial interno da resposta JSON (Previne Enumeração / IDOR)
    protected $hidden = ['id'];

    public function agendamentos()
    {
        // Força o relacionamento a usar o 'id' interno para performance de banco
        return $this->hasMany(Agendamento::class, 'paciente_id', 'id');
    }

    public function evolucoes()
    {
        return $this->hasMany(Evolucao::class, 'paciente_id', 'id');
    }
}
