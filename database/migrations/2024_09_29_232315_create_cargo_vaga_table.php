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
        Schema::create('cargo_vaga', function (Blueprint $table) {
            $table->foreignId('cargo_id');
            $table->foreignId('vaga_id');
            $table->unique(['cargo_id', 'vaga_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cargo_vaga');
    }
};
