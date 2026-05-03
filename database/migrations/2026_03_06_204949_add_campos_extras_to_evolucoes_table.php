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
        Schema::table('evolucoes', function (Blueprint $table) {
            $table->string('tipo_atendimento')->nullable();
            $table->text('sinais_vitais_encrypted')->nullable()->comment('Sinais vitais em JSON criptografado (PHI)');
            $table->text('prescricao_encrypted')->nullable()->comment('Prescrição criptografada (PHI)');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('evolucoes', function (Blueprint $table) {
            $table->dropColumn([
                'tipo_atendimento',
                'sinais_vitais_encrypted',
                'prescricao_encrypted'
            ]);
        });
    }
};
