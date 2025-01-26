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
        Schema::table('associados', function (Blueprint $table) {
            $table->string('titulo_eleitor')->nullable()->after('cpf');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('associados', function (Blueprint $table) {
            $table->dropColumn('titulo_eleitor');
        });
    }
};
