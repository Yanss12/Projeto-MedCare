<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Faz ajustes na tabela de evoluções.
     */
    public function up(): void
    {
        Schema::table('evolucoes', function (Blueprint $table) {
            // Decidimos trocar o nome da coluna de 'descricao' pra 'observacoes' pq faz mais sentido clínico
            $table->renameColumn('descricao', 'observacoes');
            
            // Novos campos úteis
            $table->json('prescricoes')->nullable(); // Remédios que o médico passou naquela consulta
            $table->string('profissional')->nullable(); // Nome do profissional pro caso dele não estar logado no sistema
        });
    }

    /**
     * Desfaz os ajustes.
     */
    public function down(): void
    {
        Schema::table('evolucoes', function (Blueprint $table) {
            $table->dropColumn(['prescricoes', 'profissional']);
            
            // Volto o nome original (observacoes -> descricao)
            $table->renameColumn('observacoes', 'descricao');
        });
    }
};
