<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Adiciona novas colunas à tabela pacientes que já existia.
     */
    public function up(): void
    {
        Schema::table('pacientes', function (Blueprint $table) {
            $table->string('endereco')->nullable(); // Onde mora
            $table->boolean('necessitatransporte')->default(false); // Flag pra saber se precisa de ambulância/carência
            $table->text('diagnostico')->nullable(); // Qual é a condição principal de saúde dele
            $table->json('alergias')->nullable(); // Guardar array de alergias (ex: ["dipirona", "amendoim"])
            $table->json('medicamentoscontrolados')->nullable(); // Array de remédios que ele já toma
        });
    }

    /**
     * Remove essas mesmas colunas inseridas em caso de rollback dessa migration.
     */
    public function down(): void
    {
        Schema::table('pacientes', function (Blueprint $table) {
            $table->dropColumn([
                'endereco',
                'necessitatransporte',
                'diagnostico',
                'alergias',
                'medicamentoscontrolados'
            ]);
        });
    }
};
