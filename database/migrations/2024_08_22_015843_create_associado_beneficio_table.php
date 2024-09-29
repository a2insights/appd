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
        Schema::create('associado_beneficio', function (Blueprint $table) {
            $table->foreignId('associado_id');
            $table->foreignId('beneficio_id');
            $table->unique(['associado_id', 'beneficio_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('associado_beneficio');
    }
};
