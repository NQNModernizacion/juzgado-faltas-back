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
        Schema::create('infractores', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tipo_id')->nullable()->constrained('estados_generales', 'id')->cascadeOnDelete();
            $table->string('documento')->nullable();
            $table->string('identificacion')->nullable();
            $table->string('nombre')->nullable();
            $table->string('domicilio')->nullable();
            $table->timestamps();
            $table->softDeletes();
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
