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
        Schema::create('suivi_post_individuels', function (Blueprint $table) {
            $table->id();
            $table->foreignId('individuelles_id')->nullable()->constrained('individuelles')->onDelete('set null');

            $table->string('situation_actuelle')->nullable();
            $table->string('temps_emploi')->nullable();
            $table->string('entreprise')->nullable();
            $table->string('secteur')->nullable();
            $table->string('lien_formation')->nullable();
            $table->string('revenu')->nullable();

            $table->string('formation_marche')->nullable();
            $table->string('competences_utilisees')->nullable();
            $table->string('recommande')->nullable();

            $table->text('difficultes')->nullable();
            $table->text('besoins')->nullable();

            $table->boolean('diplome_retire')->nullable();
            $table->text('commentaires')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('suivi_post_individuels');
    }
};
