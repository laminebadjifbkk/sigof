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
        Schema::create('parc_missions', function (Blueprint $table) {
            $table->id();
            $table->string('reference')->unique();
            $table->foreignId('vehicule_id')->constrained('parc_vehicules')->cascadeOnDelete();
            $table->foreignId('chauffeur_id')->constrained('parc_chauffeurs')->cascadeOnDelete();
            $table->string('objet');
            $table->string('lieu_depart');
            $table->string('lieu_arrivee');
            $table->date('date_depart');
            $table->date('date_retour')->nullable();
            $table->unsignedInteger('distance_km')->default(0);
            $table->decimal('indemnites_total', 12, 2)->default(0);
            $table->enum('statut', ['planifiee', 'en_cours', 'cloturee', 'annulee'])->default('planifiee');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('parc_missions');
    }
};
