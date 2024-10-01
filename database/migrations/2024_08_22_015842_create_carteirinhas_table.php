<?php

use App\Models\Associado;
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
        Schema::create('carteirinhas', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Associado::class, 'associado_id')->constrained()->cascadeOnDelete();
            $table->enum('status', ['ativa', 'cancelada', 'vencida']);
            $table->string('foto')->nullable();
            $table->string('pdf')->nullable();
            $table->date('data_emissao');
            $table->date('data_vencimento');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('carteirinhas');
    }
};
