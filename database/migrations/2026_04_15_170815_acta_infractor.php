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

        Schema::create('acta_infractores', function (Blueprint $table) {
            $table->id();
            $table->foreignId('acta_id')->constrained('actas', 'id')->cascadeOnDelete();
            $table->foreignId('infractor_id')->constrained('infractores', 'id')->cascadeOnDelete();

            // Campo extra para diferenciar el tipo de infractor
            $table->foreignId('categoria_infractor_id')->constrained('estados_generales', 'id')->cascadeOnDelete();
            $table->text('observaciones')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('infractores');
    }
};
