<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Profissional extends Model
{
    // O nome da tabela no plural, pro Laravel não se perder e criar 'profissionals'
    protected $table = 'profissionais';
    
    // Lista de informações que liberamos para salvar direto no banco
    protected $fillable = [
        'nome', 'especialidade', 'crm', 'telefone', 'email',
        'registro_interno', 'horasvoluntarias', 'disponibilidade', 'horarios', 'status'
    ];

    // Como o Laravel deve tratar o dado de certas colunas ao buscar do banco
    protected $casts = [
        'disponibilidade' => 'array', // Converte automaticamente JSON do banco para array PPH
    ];

    // Relação: Um profissional atende e tem vários agendamentos marcados
    public function agendamentos()
    {
        return $this->hasMany(Agendamento::class);
    }

    // Relação: Um profissional pode registrar várias evoluções de pacientes
    public function evolucoes()
    {
        return $this->hasMany(Evolucao::class);
    }
}
