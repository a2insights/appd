<?php

use App\Models\Associado;
use App\Models\Beneficio;
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
            $table->foreignIdFor(Associado::class, 'associado_id')->constrained()->cascadeOnDelete();
            $table->foreignIdFor(Beneficio::class, 'beneficio_id')->constrained()->cascadeOnDelete();
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
