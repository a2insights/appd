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

        Schema::create('atendimento_tipo', function (Blueprint $table) {
            $table->foreignId('atendimento_id');
            $table->foreignId('tipo_id');
            $table->unique(['atendimento_id', 'tipo_id']);
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('atendimento_tipo');
    }
};
