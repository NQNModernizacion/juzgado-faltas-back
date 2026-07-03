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
        Schema::create('cautelar_acta', function (Blueprint $table) {
            $table->id();
            $table->foreignId('acta_id')->nullable()->constrained()->onDelete('cascade');
            $table->foreignId('cautelar_id')->nullable()->constrained('estados_generales')->onDelete('cascade');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cautelar_acta');
    }
};
