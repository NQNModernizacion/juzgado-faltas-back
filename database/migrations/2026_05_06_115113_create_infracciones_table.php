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
        Schema::create('infracciones', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('tipo_infraccion_id')->nullable();
            $table->string('identificacion')->nullable();
            $table->text('descripcion')->nullable();
            $table->string('ley')->nullable();
            $table->string('grado')->nullable();
            $table->string('codigo_caja')->nullable();
            $table->string('valoracion')->nullable();
            $table->decimal('monto', 15, 2)->nullable();
            $table->decimal('monto_max', 15, 2)->nullable();
            $table->decimal('monto_minimo', 15, 2)->nullable();
            $table->boolean('admite_pago_voluntario')->nullable();
            $table->softDeletes();
            $table->timestamps();

            $table->foreign('tipo_infraccion_id')->references('id')->on('estados_generales');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('infracciones');
    }
};
