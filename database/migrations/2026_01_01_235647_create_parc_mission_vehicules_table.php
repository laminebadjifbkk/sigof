<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('parc_mission_vehicules', function (Blueprint $table) {
            $table->id();

            $table->foreignId('mission_id')
                ->constrained('parc_missions')
                ->cascadeOnDelete();

            $table->foreignId('vehicule_id')
                ->constrained('parc_vehicules')
                ->cascadeOnDelete();

            $table->foreignId('chauffeur_id')
                ->nullable()
                ->constrained('parc_chauffeurs')
                ->nullOnDelete();

            $table->integer('kilometrage_depart')->nullable();
            $table->integer('kilometrage_retour')->nullable();

            $table->timestamps();

            // 🔒 Un véhicule ne peut apparaître qu'une seule fois par mission
            $table->unique(['mission_id', 'vehicule_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('parc_mission_vehicules');
    }
};
