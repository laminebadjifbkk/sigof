<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('support_incidents', function (Blueprint $table) {
            $table->id();

            // Identification de l'incident
            $table->string('reference', 30)->unique();
            $table->string('titre', 255);
            $table->longText('description');

            // Référentiels
            $table->unsignedBigInteger('support_incident_type_id');
            $table->unsignedBigInteger('support_priorite_id');
            $table->unsignedBigInteger('support_incident_statut_id');

            // Déclarant et responsable
            $table->unsignedInteger('user_declarant_id');
            $table->unsignedInteger('user_responsable_id')->nullable();

            // Chronologie
            $table->dateTime('date_detection');
            $table->dateTime('date_declaration');
            $table->dateTime('date_prise_en_charge')->nullable();
            $table->dateTime('date_resolution')->nullable();
            $table->dateTime('date_cloture')->nullable();

            // Analyse et résolution
            $table->longText('cause')->nullable();
            $table->longText('solution')->nullable();

            // Traçabilité
            $table->unsignedInteger('user_create_id')->nullable();
            $table->unsignedInteger('user_update_id')->nullable();

            $table->timestamps();
            $table->softDeletes();

            // Clés étrangères vers les référentiels
            $table->foreign('support_incident_type_id')
                ->references('id')
                ->on('support_incident_types')
                ->restrictOnDelete();

            $table->foreign('support_priorite_id')
                ->references('id')
                ->on('support_priorites')
                ->restrictOnDelete();

            $table->foreign('support_incident_statut_id')
                ->references('id')
                ->on('support_incident_statuts')
                ->restrictOnDelete();

            // Clés étrangères vers les utilisateurs
            $table->foreign('user_declarant_id')
                ->references('id')
                ->on('users')
                ->restrictOnDelete();

            $table->foreign('user_responsable_id')
                ->references('id')
                ->on('users')
                ->nullOnDelete();

            $table->foreign('user_create_id')
                ->references('id')
                ->on('users')
                ->nullOnDelete();

            $table->foreign('user_update_id')
                ->references('id')
                ->on('users')
                ->nullOnDelete();

            // Index de consultation et de pilotage
            $table->index('date_detection');
            $table->index('date_declaration');
            $table->index('date_resolution');
            $table->index(
                ['support_incident_statut_id', 'support_priorite_id'],
                'idx_incidents_statut_priorite'
            );
            $table->index('user_responsable_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('support_incidents');
    }
};
