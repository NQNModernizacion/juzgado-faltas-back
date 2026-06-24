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
            $table->foreignId('juez_subrogante_id')->nullable()->after('juez_id')->constrained('juez', 'id')->onDelete('set null');
            $table->foreignId('secretaria_subrogante_id')->nullable()->after('secretaria_id')->constrained('secretarias', 'id')->onDelete('set null');
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
