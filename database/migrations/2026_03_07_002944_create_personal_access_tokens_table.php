<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Roda as migrations (Cria a tabela no banco).
     */
    public function up(): void
    {
        Schema::create('personal_access_tokens', function (Blueprint $table) {
            $table->id(); // ID principal da tabela
            
            // Cria os campos tokenable_id e tokenable_type para a relação polimórfica (ex: ligar o token a um usuário)
            $table->morphs('tokenable');
            
            $table->text('name'); // Nome do token (tipo "Meu PC", "Celular", etc)
            $table->string('token', 64)->unique(); // O hash do token em si
            $table->text('abilities')->nullable(); // Permissões vinculadas a esse token (opcional)
            $table->timestamp('last_used_at')->nullable(); // Quando o token foi usado pela última vez
            $table->timestamp('expires_at')->nullable()->index(); // Data e hora de expiração do token
            $table->timestamps(); // Campos padrão created_at e updated_at
        });
    }

    /**
     * Reverte as migrations (Desfaz o que foi feito no up).
     */
    public function down(): void
    {
        Schema::dropIfExists('personal_access_tokens');
    }
};
