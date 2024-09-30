<?php

use App\Models\Cargo;
use App\Models\Talento;
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
        Schema::create('cargo_talento', function (Blueprint $table) {
            $table->foreignIdFor(Cargo::class, 'cargo_id')->constrained()->cascadeOnDelete();
            $table->foreignIdFor(Talento::class, 'talento_id')->constrained()->cascadeOnDelete();
            $table->unique(['cargo_id', 'talento_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cargo_talento');
    }
};
