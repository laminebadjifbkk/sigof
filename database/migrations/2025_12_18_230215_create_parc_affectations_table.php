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
        Schema::create('parc_affectations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('vehicule_id')->constrained('parc_vehicules')->cascadeOnDelete();
            $table->foreignId('chauffeur_id')->constrained('parc_chauffeurs')->cascadeOnDelete();
            $table->date('date_debut');
            $table->date('date_fin')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('parc_affectations');
    }
};
