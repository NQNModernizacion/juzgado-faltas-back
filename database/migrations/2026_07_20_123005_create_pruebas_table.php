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
        Schema::create('pruebas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('acta_id')->constrained('actas')->onDelete('cascade');
            $table->boolean('tipo_archivo');
            $table->text('observacion')->nullable();
            $table->unsignedBigInteger('user_id'); // Sin foreign key física por estar en otra conexión (admin)
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pruebas');
    }
};
