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
        Schema::create('pacientes', function (Blueprint $table) {
            $table->id(); // PK interna para performance de JOINs (nunca exposta)
            $table->uuid('uuid')->unique(); // Identificador público da API
            $table->string('nome');
            $table->text('cpf_encrypted')->nullable()->comment('CPF criptografado (LGPD)');
            $table->text('telefone_encrypted')->nullable()->comment('Telefone criptografado (LGPD)');
            $table->date('data_nascimento')->nullable();
            $table->timestamps();
            $table->softDeletes(); // Nunca deletar fisicamente (Retenção LGPD)
            $table->index('uuid'); // Index para busca rápida por UUID
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pacientes');
    }
};
