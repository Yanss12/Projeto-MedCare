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
            $table->string('endereco')->nullable();
            $table->boolean('necessitatransporte')->default(false);
            $table->text('diagnostico')->nullable();
            $table->json('alergias')->nullable();
            $table->json('medicamentoscontrolados')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pacientes', function (Blueprint $table) {
            $table->dropColumn([
                'endereco',
                'necessitatransporte',
                'diagnostico',
                'alergias',
                'medicamentoscontrolados'
            ]);
        });
    }
};
