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
        Schema::create('parc_vehicules', function (Blueprint $table) {
            $table->id();
            $table->string('immatriculation')->unique();
            $table->string('marque');
            $table->string('modele')->nullable();
            $table->year('annee')->nullable();
            $table->string('categorie')->nullable();
            $table->enum('energie', ['diesel', 'essence', 'hybride', 'electrique'])->nullable();
            $table->decimal('consommation_moyenne', 8, 2)->nullable();
            $table->decimal('capacite_reservoir', 8, 2)->nullable();
            $table->unsignedInteger('kilometrage_actuel')->default(0);
            $table->enum('etat', ['operationnel', 'maintenance', 'hors_service'])->default('operationnel');
            $table->date('assurance_expire_le')->nullable();
            $table->date('visite_technique_expire_le')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('parc_vehicules');
    }
};
