<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Cria a tabela de vínculos entre o paciente e o profissional para formar o agendamento.
     */
    public function up(): void
    {
        Schema::create('agendamentos', function (Blueprint $table) {
            $table->id(); // ID único da consulta/agendamento
            
            // Relacionamentos: Se apagar o paciente ou profissional, esse agendamento é deletado em cascata
            $table->foreignId('paciente_id')->constrained('pacientes')->cascadeOnDelete();
            $table->foreignId('profissional_id')->constrained('profissionais')->cascadeOnDelete();
            
            $table->dateTime('data_hora'); // Quando vai acontecer o atendimento
            $table->string('status')->default('agendado'); // Status da consulta: agendado, finalizado, cancelado...
            $table->timestamps(); // Datas de criação e edição do registro
        });
    }

    /**
     * Reverte a alteração (apaga a tabela).
     */
    public function down(): void
    {
        Schema::dropIfExists('agendamentos');
    }
};
