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
        Schema::table('formulaires', function (Blueprint $table) {
            $table->string('diplome')->nullable(); // stocke le chemin du fichier
            $table->string('cv')->nullable();      // stocke le chemin du fichier
            $table->string('statut')->default('Nouvelle'); // ou null si tu préfères
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('formulaires', function (Blueprint $table) {
            $table->dropColumn(['diplome', 'cv', 'statut']);
        });
    }
};
