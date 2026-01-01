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
        Schema::table('parc_missions', function (Blueprint $table) {
            // Ajout des nouvelles colonnes
            $table->string('departement')->nullable()->after('lieu_arrivee');
            $table->string('region')->nullable()->after('departement');
            $table->text('itineraire')->nullable()->after('region');

            $table->decimal('taux_journalier', 12, 2)->nullable()->after('itineraire');
            $table->decimal('indemnite_mission', 12, 2)->nullable()->after('taux_journalier');
            $table->decimal('frais_deplacement', 12, 2)->nullable()->after('indemnite_mission');
            $table->decimal('avance', 12, 2)->nullable()->after('frais_deplacement');
            $table->decimal('reliquat', 12, 2)->nullable()->after('avance');

            $table->text('commentaires')->nullable()->after('reliquat');
            $table->text('autres')->nullable()->after('commentaires');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('parc_missions', function (Blueprint $table) {
            $table->dropColumn([
                'departement',
                'region',
                'itineraire',
                'taux_journalier',
                'indemnite_mission',
                'frais_deplacement',
                'avance',
                'reliquat',
                'commentaires',
                'autres',
            ]);
        });
    }
};