<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Incrementa a tabela de profissionais com novos dados.
     */
    public function up(): void
    {
        Schema::table('profissionais', function (Blueprint $table) {
            $table->string('registro_interno')->nullable(); // Matrícula interna da clínica/hospital
            $table->integer('horasvoluntarias')->default(0); // Contador de horas para quem trampa voluntário
            $table->json('disponibilidade')->nullable(); // Ex: dias da semana que ele atende
            $table->string('horarios')->nullable(); // Faixa de horário
            $table->string('status')->default('ativo'); // ativo, inativo, ferias...
        });
    }

    /**
     * Remove as colunas em caso de rollback.
     */
    public function down(): void
    {
        Schema::table('profissionais', function (Blueprint $table) {
            $table->dropColumn([
                'registro_interno',
                'horasvoluntarias',
                'disponibilidade',
                'horarios',
                'status'
            ]);
        });
    }
};
