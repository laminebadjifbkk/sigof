<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sessions_formation_traducteurs', function (Blueprint $table) {
            $table->id();
            $table->string('nom'); // Ex : "Formation traducteurs Espagnol - Diamniadio"
            $table->foreignId('langue_specialisation_id')
                ->nullable()
                ->constrained('langues_specialisations')
                ->nullOnDelete();
            $table->string('formateur')->nullable();
            $table->string('lieu')->nullable();
            $table->date('date_debut');
            $table->date('date_fin');
            $table->enum('statut', ['planifiee', 'en_cours', 'terminee', 'annulee'])->default('planifiee');
            $table->text('description')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sessions_formation_traducteurs');
    }
};
