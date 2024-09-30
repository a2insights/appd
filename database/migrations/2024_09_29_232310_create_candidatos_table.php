<?php

use App\Models\Talento;
use App\Models\Vaga;
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
        Schema::create('candidatos', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Vaga::class, 'vaga_id')->constrained()->cascadeOnDelete();
            $table->foreignIdFor(Talento::class, 'talento_id')->constrained()->cascadeOnDelete();
            $table->enum('status', ['nova', 'em_andamento', 'selecionado', 'desclassificado', 'finalizado'])->default('nova');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('candidatos');
    }
};
