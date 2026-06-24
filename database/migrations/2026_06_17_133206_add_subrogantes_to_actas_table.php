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
        Schema::table('actas', function (Blueprint $table) {
            $table->foreignId('juez_subrogante_id')->nullable()->constrained('juez', 'id')->onDelete('set null')->after('juez_id');
            $table->foreignId('secretaria_subrogante_id')->nullable()->constrained('secretarias', 'id')->onDelete('set null')->after('juez_subrogante_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('actas', function (Blueprint $table) {
            $table->dropForeign(['juez_subrogante_id']);
            $table->dropForeign(['secretaria_subrogante_id']);
            $table->dropColumn(['juez_subrogante_id', 'secretaria_subrogante_id']);
        });
    }
};
