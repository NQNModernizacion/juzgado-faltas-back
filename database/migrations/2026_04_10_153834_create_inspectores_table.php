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
        Schema::create('inspectores', function (Blueprint $table) {
            $table->id();
            $table->string('legajo', 100)->nullable()->index('legajo_inspector');
            $table->string('nombre', 100)->nullable();
            $table->string('apellido', 100)->nullable();
            $table->foreignId('habilitado_id')->nullable()->constrained('estados_generales', 'id')->onDelete('set null');
            $table->foreignId('oficina_id')->nullable()->constrained('oficinas', 'id')->onDelete('set null');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inspectores');
    }
};
