<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('causas', function (Blueprint $table) {
            $table->id();

            $table->foreignId('grupo_acta_id')
                ->constrained('grupos_actas')
                ->restrictOnDelete();
                // ->unique();

            $table->string('numero_juzgado')->nullable();
            $table->year('year')->nullable();
            $table->string('numero_causa')->nullable()->unique();
            $table->dateTime('fecha_alta_causa')->nullable();

            $table->unsignedBigInteger('causa_id_padre')->nullable();
            $table->dateTime('fecha_vinculacion_padre')->nullable();



            // otros campos de la causa
            $table->foreignId('estado_causa_id')->nullable()->constrained('estados_generales', 'id')->onDelete('set null');
            $table->text('observacion')->nullable();

            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('causas');
    }
};
