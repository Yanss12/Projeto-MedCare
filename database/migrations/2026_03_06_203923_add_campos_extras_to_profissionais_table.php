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
        Schema::table('profissionais', function (Blueprint $table) {
            $table->string('registro_interno')->nullable();
            $table->integer('horasvoluntarias')->default(0);
            $table->json('disponibilidade')->nullable();
            $table->string('horarios')->nullable();
            $table->string('status')->default('ativo');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('profissionais', function (Blueprint $table) {
            $table->dropColumn([
                'registro_interno',
                'horasvoluntarias',
                'disponibilidade',
                'horarios',
                'status'
            ]);
        });
    }
};
