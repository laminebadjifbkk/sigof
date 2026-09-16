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
        Schema::table('onfp_taches', function (Blueprint $table) {
            $table->unsignedInteger('ordre')->default(0)->after('progression');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('onfp_taches', function (Blueprint $table) {
            $table->dropColumn('ordre');
        });
    }
};