<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Schema::create('causas', function (Blueprint $table) {
        //     $table->id();

        //     $table->foreignId('grupo_acta_id')
        //         ->constrained('grupos_actas')
        //         ->restrictOnDelete();
        //     // ->unique();

        //     $table->foreignId('grupo_causa_id')
        //         ->constrained('grupos_causas')
        //         ->restrictOnDelete();

        //     $table->foreignId('numero_juzgado_id')->nullable()->constrained('juzgados', 'id')->onDelete('set null');
        //     $table->year('year')->nullable();
        //     $table->string('numero_causa')->nullable()->unique();
        //     $table->dateTime('fecha_alta_causa')->nullable();
        //     $table->foreignId('tipo_causa_id')->nullable()->constrained('estados_generales', 'id')->onDelete('set null');
        //     $table->foreignId('ley_causa_id')->nullable()->constrained('estados_generales', 'id')->onDelete('set null');
        //     $table->foreignId('grado_causa_id')->nullable()->constrained('estados_generales', 'id')->onDelete('set null');
        //     $table->foreignId('oficina_interna_id')->nullable()->constrained('oficina_internas', 'id')->onDelete('set null');
        //     $table->foreignId('secretaria_id')->nullable()->constrained('secretarias', 'id')->onDelete('set null');
        //     $table->foreignId('juez_id')->nullable();

        //     $table->unsignedBigInteger('causa_id_padre')->nullable();
        //     $table->dateTime('fecha_vinculacion_padre')->nullable();

        //     $table->text('caratula')->nullable();


        //     // otros campos de la causa
        //     $table->foreignId('estado_causa_id')->nullable()->constrained('estados_generales', 'id')->onDelete('set null');
        //     $table->dateTime('fecha_estado_causa')->nullable();
        //     $table->dateTime('fecha_notificado_causa')->nullable();
        //     $table->text('observacion')->nullable();

        //     $table->timestamps();
        //     $table->softDeletes();
        // });
    }

    public function down(): void
    {
        Schema::dropIfExists('causas');
    }
};
