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
        Schema::create('estados_generales', function (Blueprint $table) {
            $table->id();
            $table->string('nombre')->nullable();
            $table->string('value')->nullable();
            $table->string('label')->nullable();
            $table->string('descripcion')->nullable();
            $table->timestamps();
            // $table->unique(['nombre', 'value']);
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('estados_generales');
    }
};
