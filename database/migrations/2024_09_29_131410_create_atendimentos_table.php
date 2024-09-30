<?php

use App\Models\Associado;
use App\Models\Pessoa;
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
        Schema::create('atendimentos', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Pessoa::class, 'pessoa_id')->nullable()->constrained()->cascadeOnDelete();
            $table->foreignIdFor(Associado::class, 'associado_id')->nullable()->constrained()->cascadeOnDelete();
            $table->boolean('em_andamento')->default(true);
            $table->boolean('finalizado_automaticamente')->default(false);
            $table->dateTime('finalizado_em')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('atendimentos');
    }
};
