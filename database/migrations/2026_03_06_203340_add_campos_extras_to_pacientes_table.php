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
        Schema::table('pacientes', function (Blueprint $table) {
            $table->text('endereco_encrypted')->nullable()->comment('Endereço criptografado (LGPD)');
            $table->boolean('necessitatransporte')->default(false);
            $table->text('diagnostico_encrypted')->nullable()->comment('Diagnóstico criptografado (PHI/LGPD)');
            $table->text('alergias_encrypted')->nullable()->comment('Alergias em JSON criptografado (PHI)');
            $table->text('medicamentoscontrolados_encrypted')->nullable()->comment('Medicamentos em JSON criptografado (PHI)');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pacientes', function (Blueprint $table) {
            $table->dropColumn([
                'endereco_encrypted',
                'necessitatransporte',
                'diagnostico_encrypted',
                'alergias_encrypted',
                'medicamentoscontrolados_encrypted'
            ]);
        });
    }
};
