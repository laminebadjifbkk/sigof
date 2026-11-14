<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('support_ticket_interventions', function (Blueprint $table) {
            $table->id();

            $table->foreignId('support_ticket_id')
                ->constrained('support_tickets')
                ->restrictOnDelete();

            // Identifiant compatible avec la table users de SIGOF.
            $table->unsignedInteger('user_id');

            $table->dateTime('date_intervention');

            $table->text('diagnostic');

            $table->longText('travaux_realises');

            $table->unsignedInteger('duree_minutes')->default(0);

            $table->string('resultat', 30);

            $table->text('observations')->nullable();

            $table->timestamps();
            $table->softDeletes();

            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->restrictOnDelete();

            $table->index(
                ['support_ticket_id', 'date_intervention'],
                'support_interventions_ticket_date_idx'
            );

            $table->index(
                ['user_id', 'date_intervention'],
                'support_interventions_user_date_idx'
            );

            $table->index('resultat');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('support_ticket_interventions');
    }
};
