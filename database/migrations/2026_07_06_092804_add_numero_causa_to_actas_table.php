<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Agregar columna numero_causa como nullable al principio
        Schema::table('actas', function (Blueprint $table) {
            $table->unsignedInteger('numero_causa')->nullable()->after('inspector_2_id');
        });

        // 2. Rellenar retrospectivamente el numero_causa para registros existentes
        $contadores = [];

        DB::table('actas')->orderBy('id')->chunkById(100, function ($actas) use (&$contadores) {
            foreach ($actas as $acta) {
                $fecha = $acta->fecha_labrada ?? $acta->fecha_carga ?? $acta->created_at ?? date('Y-m-d H:i:s');
                $year = $acta->year ?: Carbon::parse($fecha)->format('Y');

                // Asegurar que el campo year no esté vacío
                if (empty($acta->year)) {
                    DB::table('actas')->where('id', $acta->id)->update(['year' => $year]);
                }

                if (!isset($contadores[$year])) {
                    $contadores[$year] = 1;
                }

                DB::table('actas')->where('id', $acta->id)->update([
                    'numero_causa' => $contadores[$year]++
                ]);
            }
        });

        // 3. Agregar índice compuesto único para [numero_causa, year]
        Schema::table('actas', function (Blueprint $table) {
            $table->unique(['numero_causa', 'year']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('actas', function (Blueprint $table) {
            $table->dropUnique(['numero_causa', 'year']);
            $table->dropColumn('numero_causa');
        });
    }
};
