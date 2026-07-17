<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('candidatures', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('users_id');
            $table->foreign('users_id')->references('id')->on('users')->cascadeOnDelete();
            $table->foreignId('langue_specialisation_id')
                ->constrained('langues_specialisations')
                ->restrictOnDelete();

            // Étape 2 — Langues (au-delà de la LV1, gérée par la relation ci-dessus)
            $table->string('certification_obtenue')->nullable();
            $table->string('diplome'); // licence, master, doctorat, certification
            $table->string('langue_maternelle');
            $table->string('niveau_francais', 10); // C1, C2...
            $table->string('langue_vivante_2')->nullable();

            // Étape 3 — Disponibilité et affectation
            $table->date('disponible_debut');
            $table->date('disponible_fin');
            $table->string('zone'); // diamniadio, dakar_centre, saly, indifferent
            $table->string('delegation_souhaitee')->nullable();

            // Étape 4 — Documents (chemins de stockage, pas les fichiers eux-mêmes)
            $table->string('piece_identite_path');
            $table->string('diplome_fichier_path');
            $table->string('certification_fichier_path')->nullable();
            $table->string('cv_path');
            $table->boolean('attestation')->default(false);

            // Traitement (dashboard admin)
            $table->enum('statut', ['nouvelle', 'nouveau', 'en_attente', 'validee', 'rejetee', 'conforme', 'non_conforme', 'forme'])->default('nouvelle');
            $table->text('commentaire_admin')->nullable();

            $table->timestamps();

            $table->index('statut');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('candidatures');
    }
};
