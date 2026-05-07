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
        Schema::create('actas', function (Blueprint $table) {
            $table->id();

            $table->foreignId('grupo_acta_id')->nullable()
                ->constrained('grupos_actas')
                ->restrictOnDelete();

            $table->string('numero_acta', 50);
            $table->string('year', 10)->nullable()->index();
            $table->foreignId('oficina_id')->nullable()->constrained('oficinas', 'id')->onDelete('set null');
            $table->dateTime('fecha_labrada')->nullable();
            $table->dateTime('fecha_carga')->nullable();
            $table->foreignId('tipo_id')->nullable()->constrained('estados_generales', 'id')->onDelete('set null');
            $table->foreignId('sub_tipo_id')->nullable()->constrained('estados_generales', 'id')->onDelete('set null');
            $table->foreignId('ley_id')->nullable()->constrained('estados_generales', 'id')->onDelete('set null');

            $table->string('lugar', 255)->nullable();

            $table->foreignId('calle_id')->nullable()->constrained('calles', 'id')->onDelete('set null');
            $table->unsignedInteger('numero_calle')->nullable();
            $table->foreignId('cruce_id')->nullable()->constrained('calles', 'id')->onDelete('set null');

            $table->foreignId('estado_acta_id')->nullable()->constrained('estados_generales', 'id')->onDelete('set null');
            $table->dateTime('fecha_estado')->nullable();

            $table->boolean('desestimada')->nullable();
            // $table->foreignId('motivo_desestimado_id')->nullable()->constrained('estados_generales', 'id', "motivo_desestimado")->onDelete('set null');

            // $table->foreignId('grado_acta_id')->nullable()->constrained('estados_generales', 'id', "grado_acta")->onDelete('set null');
            $table->dateTime('fecha_notificado')->nullable();

            $table->foreignId('inspector_1_id')->nullable()->constrained('inspectores', 'id')->onDelete('set null');
            $table->foreignId('inspector_2_id')->nullable()->constrained('inspectores', 'id')->onDelete('set null');

            // $table->longText('infracciones')->nullable();
            // CUASA
            $table->foreignId('numero_juzgado_id')->nullable()->constrained('juzgados', 'id')->onDelete('set null');
            // $table->dateTime('fecha_alta_causa')->nullable();
            $table->foreignId('oficina_interna_id')->nullable()->constrained('oficina_internas', 'id')->onDelete('set null');
            $table->foreignId('secretaria_id')->nullable()->constrained('secretarias', 'id')->onDelete('set null');
            $table->foreignId('juez_id')->nullable();

            $table->unsignedBigInteger('causa_id_padre')->nullable();
            $table->dateTime('fecha_vinculacion_padre')->nullable();

            $table->text('caratula')->nullable();

            // otros campos de la causa
            $table->foreignId('estado_causa_id')->nullable()->constrained('estados_generales', 'id')->onDelete('set null');
            $table->dateTime('fecha_estado_causa')->nullable();
            $table->dateTime('fecha_notificado_causa')->nullable();
            $table->text('observacion')->nullable();


            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('actas');
    }
};
