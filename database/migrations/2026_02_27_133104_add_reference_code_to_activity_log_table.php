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
        /**
         * $table agregamos:
         * - reference_code: un código de referencia único para cada error, que se puede 
         * usar para rastrear y correlacionar errores en los logs y en las notificaciones 
         * de Discord.
         * - el batch_uuid ya existe en la tabla activity_log, lo dejamos para mantener la relación con los logs de errores.
         * - unique en reference_code para evitar duplicados y asegurar que cada error tenga un código de referencia único.
         */
        Schema::table('activity_log', function (Blueprint $table) {
            // el batch uuid 
            $table->string('reference_code',20)->nullable()->after('batch_uuid');
            $table->unique('reference_code');
            #$table->index('reference_code');
            //
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('activity_log', function (Blueprint $table) {
            //
            $table->dropUnique(['reference_code']);
            #$table->dropIndex(['reference_code']);
            $table->dropColumn('reference_code');
        });
    }
};
