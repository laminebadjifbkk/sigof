<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('commissionagrements', function (Blueprint $table) {
            // Ajout de la clé étrangère vers commissionmembres
            $table->foreignId('secretaire_id')
                ->nullable()
                ->constrained('commissionmembres')
                ->onDelete('set null')
                ->after('chef_id');

            // Ajout de la colonne recommandations
            $table->longText('recommandations')
                ->nullable()
                ->after('fin_commission');
        });
    }

    public function down(): void
    {
        Schema::table('commissionagrements', function (Blueprint $table) {
            $table->dropForeign(['secretaire_id']);
            $table->dropColumn(['secretaire_id', 'recommandations']);
        });
    }
};
