<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('estados', function (Blueprint $table) {
            $table->unsignedTinyInteger('codigo_ibge')->primary();
            $table->char('uf', 2)->unique();
            $table->string('nome');
        });

        Schema::create('municipios', function (Blueprint $table) {
            $table->unsignedInteger('codigo_ibge')->primary();
            $table->string('nome');
            $table->unsignedTinyInteger('estado_codigo_ibge');
            $table->foreign('estado_codigo_ibge')->references('codigo_ibge')->on('estados');
            $table->index(['estado_codigo_ibge', 'nome']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('municipios');
        Schema::dropIfExists('estados');
    }
};
