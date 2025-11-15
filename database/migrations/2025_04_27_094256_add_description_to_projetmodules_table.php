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
        Schema::table('projetmodules', function (Blueprint $table) {
            $table->text('description')->nullable()->after('effectif'); // Ajouter la colonne 'description' après la colonne 'effectif'
            $table->string('statut', 200)->nullable()->after('projets_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('projetmodules', function (Blueprint $table) {
            $table->dropColumn('description'); // Supprimer la colonne 'description' si elle existe
            $table->dropColumn('statut'); // Supprimer la colonne 'description' si elle existe
        });
    }
};
