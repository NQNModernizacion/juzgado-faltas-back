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
        Schema::create('acta_infraccion', function (Blueprint $table) {
            $table->id();
            $table->foreignId('acta_id')->constrained('actas')->onDelete('cascade');
            $table->foreignId('infraccion_id')->constrained('infracciones')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('acta_infraccion');
    }
};
