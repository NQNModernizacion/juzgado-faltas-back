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
        Schema::create('estados_procesales', function (Blueprint $table) {
            $table->id();
            $table->string('estado', 10)->unique();
            $table->string('descripcion');
            $table->unsignedInteger('tipo')->nullable();
            $table->boolean('es_antec')->default(false);
            $table->unsignedInteger('antec_vig_dias')->nullable();
            $table->unsignedInteger('porc_bonif')->nullable();
            $table->unsignedInteger('bonif_vig_dias')->nullable();
            $table->boolean('gen_notif')->default(false);
            $table->unsignedInteger('notif_cant_dias')->nullable();
            $table->boolean('form_autom')->nullable();
            $table->boolean('perm_pago')->default(true);
            $table->boolean('perm_plan')->default(true);
            $table->boolean('perm_vol')->default(true);
            $table->boolean('desestima')->default(false);
            $table->string('tipo_causa')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('estados_procesales');
    }
};
