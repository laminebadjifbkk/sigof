<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('onfp_activites', function (Blueprint $table) {
            $table->id();

            $table->string('reference', 50)->unique();

            /*
            |--------------------------------------------------------------------------
            | Référentiel existant : directions
            |--------------------------------------------------------------------------
            | directions.id = INT UNSIGNED
            */
            $table->unsignedInteger('direction_id')->nullable();

            $table->foreign('direction_id')
                ->references('id')
                ->on('directions')
                ->restrictOnDelete();

            /*
            |--------------------------------------------------------------------------
            | Type d'activité
            |--------------------------------------------------------------------------
            */
            $table->foreignId('type_id')
                ->nullable()
                ->constrained('onfp_activite_types')
                ->restrictOnDelete();

            /*
            |--------------------------------------------------------------------------
            | Hiérarchie d'activités
            |--------------------------------------------------------------------------
            */
            $table->foreignId('parent_id')
                ->nullable();

            $table->foreign('parent_id')
                ->references('id')
                ->on('onfp_activites')
                ->restrictOnDelete();

            /*
            |--------------------------------------------------------------------------
            | Informations générales
            |--------------------------------------------------------------------------
            */
            $table->string('titre', 255);
            $table->text('description')->nullable();

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
            $table->date('date_enclenchement')->nullable();
            $table->date('date_execution_prevue')->nullable();
            $table->date('date_fin_prevue')->nullable();

            /*
            |--------------------------------------------------------------------------
            | Réalisation
            |--------------------------------------------------------------------------
            */
            $table->date('date_execution_reelle')->nullable();
            $table->date('date_fin_reelle')->nullable();

            /*
            |--------------------------------------------------------------------------
            | État de santé / pilotage
            |--------------------------------------------------------------------------
            */
            $table->string('etat_sante', 30)
                ->default('normal');

            $table->text('observation')->nullable();

            /*
            |--------------------------------------------------------------------------
            | Employé ayant créé / modifié l'activité
            |--------------------------------------------------------------------------
            | À adapter selon le type réel de employees.id.
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
                ['direction_id', 'statut'],
                'onfp_activites_direction_statut_idx'
            );

            $table->index(
                ['type_id', 'statut'],
                'onfp_activites_type_statut_idx'
            );

            $table->index(
                ['date_fin_prevue'],
                'onfp_activites_date_fin_prevue_idx'
            );

            $table->index(
                ['etat_sante'],
                'onfp_activites_etat_sante_idx'
            );

            $table->index(
                ['progression'],
                'onfp_activites_progression_idx'
            );
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('onfp_activites');
    }
};
