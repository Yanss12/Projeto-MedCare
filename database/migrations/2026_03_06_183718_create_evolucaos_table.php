<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Cria a tabela de evoluções (prontuário/registro clínico do paciente).
     */
    public function up(): void
    {
        Schema::create('evolucoes', function (Blueprint $table) {
            $table->id(); // ID único do registro

            // Informa de qual paciente e de qual profissional é essa anotação (apaga em cascata se excluir os pais)
            $table->foreignId('paciente_id')->constrained('pacientes')->cascadeOnDelete();
            $table->foreignId('profissional_id')->constrained('profissionais')->cascadeOnDelete();
            
            $table->text('descricao'); // O que aconteceu na consulta/atendimento
            $table->date('data_registro'); // Data de quando a evolução ocorreu
            $table->timestamps();
        });
    }

    /**
     * Reverte a criação da tabela.
     */
    public function down(): void
    {
        Schema::dropIfExists('evolucoes');
    }
};
