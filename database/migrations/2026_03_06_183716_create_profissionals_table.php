<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Cria a tabela de profissionais no banco.
     */
    public function up(): void
    {
        Schema::create('profissionais', function (Blueprint $table) {
            $table->id(); // ID único
            $table->string('nome'); // Nome completo do profissional
            $table->string('especialidade'); // Qual a área de atuação (ex: Pediatra, Fisioterapeuta)
            $table->string('crm')->nullable(); // Registro profissional (CRM, COREN, etc). Opcional pra abranger outras áreas.
            $table->string('telefone')->nullable(); // Contato
            $table->string('email')->nullable(); // E-mail para contato
            $table->timestamps(); // Colunas created_at e updated_at
        });
    }

    /**
     * Remove a tabela em caso de reversão da migration.
     */
    public function down(): void
    {
        Schema::dropIfExists('profissionais');
    }
};
