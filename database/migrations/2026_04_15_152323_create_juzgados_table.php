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
        Schema::create('juzgados', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('numero_juzgado')->nullable();
            $table->string('descripcion')->nullable();
            $table->foreignId('juez_id')->nullable()->constrained('juez', 'id')->onDelete('set null');
            $table->foreignId('juez_subrogante_id')->nullable()->constrained('juez', 'id')->onDelete('set null');
            $table->boolean('subrogado')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('juzgados');
    }
};
