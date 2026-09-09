<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('onfp_sous_activites', function (Blueprint $table) {
            $table->id();

            /*
            |--------------------------------------------------------------------------
            | Activité parente
            |--------------------------------------------------------------------------
            | onfp_activites.id est BIGINT UNSIGNED
            */
            $table->foreignId('activite_id')
                ->constrained('onfp_activites')
                ->restrictOnDelete();

            /*
            |--------------------------------------------------------------------------
            | Identification
            |--------------------------------------------------------------------------
            */
            $table->string('reference', 50)->unique();

            $table->string('titre', 255);
            $table->text('description')->nullable();

            /*
            |--------------------------------------------------------------------------
            | Pilotage
            |--------------------------------------------------------------------------
            */
            $table->string('statut', 50)
                ->default('a_faire');

            $table->string('priorite', 30)
                ->default('normale');

            $table->unsignedTinyInteger('progression')
                ->default(0);

            /*
            |--------------------------------------------------------------------------
            | Planification
            |--------------------------------------------------------------------------
            */
            $table->date('date_debut')->nullable();
            $table->date('date_fin_prevue')->nullable();
            $table->date('date_fin_reelle')->nullable();

            /*
            |--------------------------------------------------------------------------
            | Observations
            |--------------------------------------------------------------------------
            */
            $table->text('observation')->nullable();

            /*
            |--------------------------------------------------------------------------
            | Traçabilité
            |--------------------------------------------------------------------------
            | employees.id = INT UNSIGNED
            */
            $table->unsignedInteger('created_by')->nullable();
            $table->unsignedInteger('updated_by')->nullable();

            $table->foreign('created_by')
                ->references('id')
                ->on('employees')
                ->restrictOnDelete();

            $table->foreign('updated_by')
                ->references('id')
                ->on('employees')
                ->restrictOnDelete();

            /*
            |--------------------------------------------------------------------------
            | Dates système
            |--------------------------------------------------------------------------
            */
            $table->timestamps();
            $table->softDeletes();

            /*
            |--------------------------------------------------------------------------
            | Index
            |--------------------------------------------------------------------------
            */
            $table->index(
                ['activite_id', 'statut'],
                'onfp_sous_activites_activite_statut_idx'
            );

            $table->index(
                ['date_fin_prevue'],
                'onfp_sous_activites_date_fin_prevue_idx'
            );
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('onfp_sous_activites');
    }
};
