<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Exécute les migrations.
     */
    public function up(): void
    {
        Schema::table('commissionmembres', function (Blueprint $table) {
            $table->string('statut')->nullable()->after('signature');
        });
    }

    /**
     * Annule les migrations.
     */
    public function down(): void
    {
        Schema::table('commissionmembres', function (Blueprint $table) {
            $table->dropColumn('statut');
        });
    }
};
