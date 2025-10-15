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
        Schema::table('commissionagrements', function (Blueprint $table) {
            $table->foreignId('chef_id')
                ->nullable()->after('fin_commission')
                ->constrained('commissionmembres')
                ->onDelete('set null');
        });
    }

    /**
     * Annule les migrations.
     */
    public function down(): void
    {
        Schema::table('commissionagrements', function (Blueprint $table) {
            $table->dropForeign(['chef_id']);
            $table->dropColumn('chef_id');
        });
    }
};
