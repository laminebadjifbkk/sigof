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
        Schema::table('lettrevaluations', function (Blueprint $table) {
            $table->string('lettre_mission_dec', 200)->nullable();
            $table->dateTime('date_lettre_dec')->nullable();
            $table->text('file_lettre_dec')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('lettrevaluations', function (Blueprint $table) {
            $table->dropUnique(['lettre_mission_dec']);
            $table->dropUnique(['date_lettre_dec']);
            $table->dropUnique(['file_lettre_dec']);
        });
    }
};
