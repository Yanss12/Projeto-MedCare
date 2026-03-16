<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Cria a tabela de pacientes no banco de dados.
     */
    public function up(): void
    {
        Schema::create('pacientes', function (Blueprint $table) {
            $table->id(); // ID único do paciente
            $table->string('nome'); // Nome completo
            $table->string('cpf')->unique()->nullable(); // CPF, não pode repetir. Opcional caso a pessoa não tenha na hora.
            $table->string('telefone')->nullable(); // Contato
            $table->date('data_nascimento')->nullable(); // Data de nasc
            $table->timestamps(); // Registra quando o paciente foi criado e atualizado pela última vez
        });
    }

    /**
     * Apaga a tabela caso o comando de rollback seja executado.
     */
    public function down(): void
    {
        Schema::dropIfExists('pacientes');
    }
};
