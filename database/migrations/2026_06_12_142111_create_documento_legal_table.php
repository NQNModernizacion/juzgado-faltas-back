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
        Schema::create('documento_legal', function (Blueprint $table) {
            $table->id();
            $table->foreignId('plantilla_documento_id')->nullable()->constrained('plantilla_documentos');
            $table->foreignId('acta_id')->nullable()->constrained('actas');
            $table->string('tipo')->nullable();
            $table->longText('contenido_html');
            $table->json('metadata')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('documento_legal');
    }
};
