<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('parc_type_missions', function (Blueprint $table) {
            $table->id();
            $table->string('libelle')->unique(); // Exemple : ONFP, Projet, Programme, Autre
            $table->timestamps();
        });

        // ✅ Ajout de la clé étrangère dans parc_missions
        Schema::table('parc_missions', function (Blueprint $table) {
            $table->foreignId('type_mission_id')
                  ->nullable()
                  ->constrained('parc_type_missions')
                  ->cascadeOnDelete()
                  ->after('chauffeur_id');
        });
    }

    public function down(): void
    {
        Schema::table('parc_missions', function (Blueprint $table) {
            $table->dropConstrainedForeignId('type_mission_id');
        });

        Schema::dropIfExists('parc_type_missions');
    }
};