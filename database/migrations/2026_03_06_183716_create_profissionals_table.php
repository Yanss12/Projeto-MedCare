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
        Schema::create('profissionais', function (Blueprint $table) {
            $table->id(); // PK interna para relacionamentos
            $table->uuid('uuid')->unique(); // Identificador público da API
            $table->string('nome');
            $table->string('especialidade');
            $table->text('crm_encrypted')->nullable()->comment('CRM criptografado (PII)');
            $table->text('telefone_encrypted')->nullable()->comment('Telefone criptografado (PII)');
            $table->text('email_encrypted')->nullable()->comment('Email criptografado (PII)');
            $table->timestamps();
            $table->softDeletes(); // Nunca deletar fisicamente
            $table->index('uuid'); // Index para busca rápida
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('profissionais');
    }
};
