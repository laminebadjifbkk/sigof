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
        Schema::create('suivi_post_groupements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('projets_id')->nullable()->constrained('projets')->onDelete('set null');

            $table->string('activite_principale')->nullable();
            $table->integer('nombre_membres')->nullable();
            $table->integer('nombre_femmes')->nullable();
            $table->integer('nombre_hommes')->nullable();

            $table->string('application_acquis')->nullable();
            $table->text('activites_developpees')->nullable();

            $table->boolean('augmentation_production')->nullable();
            $table->boolean('amelioration_qualite')->nullable();
            $table->boolean('nouveaux_marches')->nullable();
            $table->integer('emplois_crees')->nullable();
            $table->boolean('augmentation_revenus')->nullable();

            $table->text('difficultes')->nullable();
            $table->text('besoins')->nullable();

            $table->string('satisfaction')->nullable();
            $table->string('recommandation')->nullable();

            $table->boolean('attestation_retiree')->nullable();
            $table->text('commentaires')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('suivi_post_groupements');
    }
};
