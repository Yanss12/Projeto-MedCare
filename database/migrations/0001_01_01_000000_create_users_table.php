<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Roda a migration (Cria as tabelas de controle de acesso padrão do Laravel).
     */
    public function up(): void
    {
        // Cria a tabela principal de usuários do sistema
        Schema::create('users', function (Blueprint $table) {
            $table->id(); // ID único
            $table->string('name'); // Nome do usuário
            $table->string('email')->unique(); // E-mail único para login
            $table->timestamp('email_verified_at')->nullable(); // (Opcional) Data de verificação de e-mail
            $table->string('password'); // Senha criptografada
            $table->rememberToken(); // Token pra funcionalidade de "lembrar-me" no login
            $table->timestamps(); // Cria as colunas created_at e updated_at
        });

        // Tabela para guardar os tokens de redefinição de senha
        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        // Tabela que controla as sessões ativas (quem tá logado, IP, navegador, etc)
        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
    }

    /**
     * Reverte a migration (Apaga as tabelas caso precise dar rollback).
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('sessions');
    }
};
