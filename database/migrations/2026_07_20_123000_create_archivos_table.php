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
        Schema::create('archivos', function (Blueprint $table) {
            $table->id();
            $table->nullableMorphs('archivable');
            $table->string('path_archivo');
            $table->string('nombre_original');
            $table->string('extension');
            $table->unsignedInteger('size');
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
        Schema::dropIfExists('archivos');
    }
};
