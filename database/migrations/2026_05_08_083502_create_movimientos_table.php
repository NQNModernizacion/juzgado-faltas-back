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
        Schema::create('movimientos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('acta_id')->constrained('actas')->onDelete('cascade');
            $table->foreignId('oficina_id_origen')->nullable()->constrained('oficina_internas')->onDelete('cascade');
            $table->foreignId('oficina_id_destino')->nullable()->constrained('oficina_internas')->onDelete('cascade');
            $table->string('motivo')->nullable();
            $table->string('fojas')->nullable();
            $table->dateTime('fecha_movimiento')->nullable();
            $table->dateTime('fecha_vecimiento')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('movimientos');
    }
};
