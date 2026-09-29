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
        Schema::table('documento_legal', function (Blueprint $table) {
            $table->string('estado')->default('activo')->after('tipo');
            $table->text('motivo_anulacion')->nullable()->after('estado');
            $table->foreignId('documento_reemplazado_id')->nullable()->after('motivo_anulacion')->constrained('documento_legal')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('documento_legal', function (Blueprint $table) {
            $table->dropForeign(['documento_reemplazado_id']);
            $table->dropColumn(['estado', 'motivo_anulacion', 'documento_reemplazado_id']);
        });
    }
};
