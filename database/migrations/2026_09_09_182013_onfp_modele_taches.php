<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('onfp_modele_taches', function (Blueprint $table) {
            $table->id();

            /*   $table->foreignId('modele_id')
                ->constrained('onfp_activite_modeles')
                ->restrictOnDelete();

            $table->foreignId('modele_sous_activite_id')
                ->nullable()
                ->constrained('onfp_modele_sous_activites')
                ->restrictOnDelete(); */

            $table->foreignId('modele_id')
                ->constrained('onfp_activite_modeles')
                ->restrictOnDelete();

            $table->foreignId('modele_sous_activite_id')
                ->nullable()
                ->constrained('onfp_modele_sous_activites')
                ->restrictOnDelete();

            $table->string('titre', 255);
            $table->text('description')->nullable();

            $table->unsignedInteger('ordre')->default(0);

            $table->unsignedInteger('duree_estimee')->nullable();

            $table->string('priorite', 30)->default('normale');

            $table->timestamps();

            $table->index(['modele_id', 'ordre']);
            $table->index(['modele_sous_activite_id', 'ordre']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('onfp_modele_taches');
    }
};
