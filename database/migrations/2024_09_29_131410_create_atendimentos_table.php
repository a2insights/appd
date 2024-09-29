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
        Schema::disableForeignKeyConstraints();

        Schema::create('atendimentos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tipo_id');
            $table->foreignId('pessoa_id');
            $table->foreignId('associado_id');
            $table->boolean('em_andamento')->default(true);
            $table->boolean('finalizado_automaticamente')->default(false);
            $table->dateTime('finalizado_em');
            $table->timestamps();
        });

        Schema::enableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('atendimentos');
    }
};
