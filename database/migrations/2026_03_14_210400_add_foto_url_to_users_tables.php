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
            $table->string('foto_url', 2048)->nullable()->after('idade');
        });

        Schema::table('profissionais', function (Blueprint $table) {
            $table->string('foto_url', 2048)->nullable()->after('status');
        });
    }

    /**
     * Reverse the migrations.
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
