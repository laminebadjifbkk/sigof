<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('support_incident_tickets', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('support_incident_id');
            $table->unsignedBigInteger('support_ticket_id');

            // Utilisateur ayant associé le ticket à l'incident
            $table->unsignedInteger('user_create_id')->nullable();

            $table->timestamps();

            // Relations avec les incidents et les tickets
            $table->foreign('support_incident_id')
                ->references('id')
                ->on('support_incidents')
                ->restrictOnDelete();

            $table->foreign('support_ticket_id')
                ->references('id')
                ->on('support_tickets')
                ->restrictOnDelete();

            // Traçabilité de l'association
            $table->foreign('user_create_id')
                ->references('id')
                ->on('users')
                ->nullOnDelete();

            // Empêcher les associations en double
            $table->unique(
                ['support_incident_id', 'support_ticket_id'],
                'support_incident_ticket_unique'
            );

            // Faciliter la recherche des incidents associés à un ticket
            $table->index('support_ticket_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('support_incident_tickets');
    }
};
