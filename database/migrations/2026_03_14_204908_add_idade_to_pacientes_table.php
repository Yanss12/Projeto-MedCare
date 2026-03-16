<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Adiciona o campo idade direto na tabela de pacientes (pra facilitar não ter que calcular sempre).
     */
    public function up(): void
    {
        Schema::table('pacientes', function (Blueprint $table) {
            // Fica logo depois da data de nascimento pra organizar bonitinho
            $table->integer('idade')->nullable()->after('data_nascimento');
        });
    }

    /**
     * Apaga a coluna se der rollback.
     */
    public function down(): void
    {
        Schema::table('pacientes', function (Blueprint $table) {
            $table->dropColumn('idade');
        });
    }
};
