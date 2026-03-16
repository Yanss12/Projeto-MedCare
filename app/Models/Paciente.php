<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Paciente extends Model
{
    // Campos que podem ser salvos no banco de uma vez so (mass assignment)
    protected $fillable = [
        'nome', 'cpf', 'telefone', 'data_nascimento',
        'endereco', 'necessitatransporte', 'diagnostico', 'alergias', 'medicamentoscontrolados', 'idade', 'foto_url'
    ];

    // Forca a conversao de alguns campos para tipos especificos quando vem do banco
    protected $casts = [
        'alergias'               => 'array',   // Salva e le como array (embora no banco seja um JSON)
        'medicamentoscontrolados'=> 'array',   // Salva e le como array
        'necessitatransporte'    => 'boolean', // Converte para booleano (true/false)
    ];

    // ===== PROTECAO DO CPF (LGPD) =====
    // O CPF e um dado pessoal sensivel. Criptografamos antes de salvar no banco
    // e descriptografamos automaticamente ao ler. Transparente para o resto do codigo.

    /**
     * Mutator: criptografa o CPF antes de salvar no banco.
     * Usa a APP_KEY do .env como chave de criptografia (AES-256).
     */
    public function setCpfAttribute(?string $value): void
    {
        // So criptografa se o valor nao for nulo
        $this->attributes['cpf'] = $value ? encrypt($value) : null;
    }

    /**
     * Accessor: descriptografa o CPF automaticamente ao acessar o campo.
     * Se o valor nao for um texto criptografado valido, retorna null com seguranca.
     */
    public function getCpfAttribute(?string $value): ?string
    {
        if (!$value) {
            return null;
        }

        try {
            return decrypt($value);
        } catch (\Exception $e) {
            // Se a descriptografia falhar (ex: dado antigo nao criptografado), retorna o valor bruto
            return $value;
        }
    }

    // Relacionamento: Um paciente pode ter varios agendamentos
    public function agendamentos()
    {
        return $this->hasMany(Agendamento::class);
    }

    // Relacionamento: Um paciente pode ter varias evolucoes clinicas/anotacoes
    public function evolucoes()
    {
        return $this->hasMany(Evolucao::class);
    }
}

