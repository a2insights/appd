<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('associados', function (Blueprint $table) {
            $table->date('data_associacao')->nullable()->after('status');
        });

        DB::statement('UPDATE associados SET data_associacao = DATE(created_at) WHERE created_at IS NOT NULL');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('associados', function (Blueprint $table) {
            $table->dropColumn('data_associacao');
        });
    }
};
