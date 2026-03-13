<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Paciente extends Model
{
    protected $fillable = [
        'nome', 'cpf', 'telefone', 'data_nascimento', 'idade',
        'endereco', 'necessitatransporte', 'diagnostico', 'alergias', 'medicamentoscontrolados'
    ];

    protected $casts = [
        'alergias' => 'array',
        'medicamentoscontrolados' => 'array',
        'necessitatransporte' => 'boolean',
    ];

    protected $appends = ['idade'];

    public function getIdadeAttribute()
    {
        if ($this->data_nascimento) {
            return Carbon::parse($this->data_nascimento)->age;
        }
        return null;
    }

    public function setIdadeAttribute($value)
    {
        if ($value) {
            $this->attributes['data_nascimento'] = now()->subYears($value)->format('Y-01-01');
        } else {
            $this->attributes['data_nascimento'] = null;
        }
    }

    public function agendamentos()
    {
        return $this->hasMany(Agendamento::class);
    }

    public function evolucoes()
    {
        return $this->hasMany(Evolucao::class);
    }
}
