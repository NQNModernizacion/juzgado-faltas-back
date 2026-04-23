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
        //
        Schema::create('acta_padron', function (Blueprint $table) {
            $table->id();
            $table->foreignId('acta_id')->constrained('actas', 'id')->cascadeOnDelete();
            $table->foreignId('padron_id')->constrained('padrones', 'id')->cascadeOnDelete();
            $table->foreignId('categoria_padron_id')->constrained('estados_generales', 'id')->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
        Schema::dropIfExists('acta_padron');
    }
};
