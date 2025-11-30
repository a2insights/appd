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
        Schema::table('segmentacoes', function (Blueprint $table) {
            $table->foreignId('grupo_segmentacao_id')->nullable()->constrained('grupos_segmentacao')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('segmentacoes', function (Blueprint $table) {
            $table->dropForeign(['grupo_segmentacao_id']);
            $table->dropColumn('grupo_segmentacao_id');
        });
    }
};
