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
        Schema::create('acta_estado_procesal', function (Blueprint $table) {
            $table->id();
            $table->foreignId('acta_id')->constrained('actas')->cascadeOnDelete();
            $table->foreignId('estado_procesal_id')->constrained('estados_procesales')->cascadeOnDelete();
            $table->dateTime('fecha');
            $table->text('observacion')->nullable();
            $table->foreignId('infractor_id')->nullable()->constrained('infractores')->nullOnDelete();
            $table->string('imputado_datos')->nullable();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('acta_estado_procesal');
    }
};
