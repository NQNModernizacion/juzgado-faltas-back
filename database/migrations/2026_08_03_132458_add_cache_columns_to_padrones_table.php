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
        Schema::table('padrones', function (Blueprint $table) {
            $table->json('data_cache')->nullable()->after('nombre');
            $table->timestamp('fecha_actualizacion')->nullable()->after('data_cache');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('padrones', function (Blueprint $table) {
            $table->dropColumn(['data_cache', 'fecha_actualizacion']);
        });
    }
};
