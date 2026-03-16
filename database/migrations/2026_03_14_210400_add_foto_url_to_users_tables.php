<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Adiciona suporte a foto de perfil nos pacientes e profissionais.
     */
    public function up(): void
    {
        // Add URL da foto no paciente
        Schema::table('pacientes', function (Blueprint $table) {
            $table->string('foto_url', 2048)->nullable()->after('idade');
        });

        // Add URL da foto no profissional
        Schema::table('profissionais', function (Blueprint $table) {
            $table->string('foto_url', 2048)->nullable()->after('status');
        });
    }

    /**
     * Remove os campos de foto do banco.
     */
    public function down(): void
    {
        Schema::table('pacientes', function (Blueprint $table) {
            $table->dropColumn('foto_url');
        });

        Schema::table('profissionais', function (Blueprint $table) {
            $table->dropColumn('foto_url');
        });
    }
};
