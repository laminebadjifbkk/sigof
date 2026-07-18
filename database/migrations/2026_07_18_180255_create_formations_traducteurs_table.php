<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('formations_traducteurs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('candidature_id')
                ->unique() // un traducteur n'a qu'un seul parcours de formation actif
                ->constrained('candidatures')
                ->cascadeOnDelete();
            $table->foreignId('session_formation_id')
                ->nullable() // peut être en attente d'affectation à une session
                ->constrained('sessions_formation_traducteurs')
                ->nullOnDelete();
            $table->enum('statut_formation', [
                'non_inscrit',   // validé mais pas encore affecté à une session
                'inscrit',       // affecté à une session, formation pas commencée
                'en_cours',      // formation en cours
                'complete',      // formation terminée avec succès
                'absent',        // n'a pas suivi la formation
            ])->default('non_inscrit');
            $table->string('attestation_path')->nullable();
            $table->date('date_attestation')->nullable();
            $table->text('commentaire')->nullable();
            $table->timestamps();

            $table->index('statut_formation');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('formations_traducteurs');
    }
};