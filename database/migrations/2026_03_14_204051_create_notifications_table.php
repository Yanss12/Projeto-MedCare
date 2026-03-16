<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Cria a tabela para gerenciar as notificações do sistema.
     */
    public function up(): void
    {
        Schema::create('notifications', function (Blueprint $table) {
            $table->id(); // ID único
            
            // Relacionamento com usuário: qual usuário vai receber esse aviso
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            
            $table->string('message'); // O texto da notificação
            $table->boolean('is_read')->default(false); // Flag pra saber se já foi lida
            $table->timestamps();
        });
    }

    /**
     * Remove as notificações do banco.
     */
    public function down(): void
    {
        Schema::dropIfExists('notifications');
    }
};
