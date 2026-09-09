<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('onfp_activite_modeles', function (Blueprint $table) {
            $table->id();

            /*
            |--------------------------------------------------------------------------
            | Identification du modèle
            |--------------------------------------------------------------------------
            */
            $table->string('code', 100)->unique();
            $table->string('nom', 255);

            /*
            |--------------------------------------------------------------------------
            | Type d'activité
            |--------------------------------------------------------------------------
            | onfp_activite_types.id = BIGINT UNSIGNED
            */
            $table->foreignId('type_id')
                ->nullable()
                ->constrained('onfp_activite_types')
                ->restrictOnDelete();

            /*
            |--------------------------------------------------------------------------
            | Direction
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
            | Description et durée
            |--------------------------------------------------------------------------
            */
            $table->text('description')->nullable();

            // Durée estimée en jours
            $table->unsignedInteger('duree_estimee')->nullable();

            $table->boolean('actif')->default(true);

            /*
            |--------------------------------------------------------------------------
            | Traçabilité
            |--------------------------------------------------------------------------
            | employees.id = INT UNSIGNED
            |--------------------------------------------------------------------------
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
                ['direction_id', 'actif'],
                'onfp_activite_modeles_direction_actif_idx'
            );

            $table->index(
                ['type_id', 'actif'],
                'onfp_activite_modeles_type_actif_idx'
            );
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('onfp_activite_modeles');
    }
};
