<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('agendamentos', function (Blueprint $table) {
            $table->id(); // PK interna para relacionamentos
            $table->uuid('uuid')->unique(); // Identificador público da API
            $table->foreignId('paciente_id')->constrained('pacientes')->cascadeOnDelete();
            $table->foreignId('profissional_id')->constrained('profissionais')->cascadeOnDelete();
            $table->dateTime('data_hora');
            $table->string('status')->default('agendado');
            $table->timestamps();
            $table->softDeletes(); // Nunca deletar fisicamente (Retenção LGPD)
            $table->index('uuid'); // Index para busca rápida
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('agendamentos');
    }
};
