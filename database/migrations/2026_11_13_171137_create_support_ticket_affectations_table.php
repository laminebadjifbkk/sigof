<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('support_ticket_affectations', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('support_ticket_id');

            // Technicien précédemment affecté
            $table->unsignedInteger('ancien_user_assigne_id')->nullable();

            // Nouveau technicien affecté
            $table->unsignedInteger('nouveau_user_assigne_id');

            // Agent ayant effectué l'affectation
            $table->unsignedInteger('affecte_par_user_id');

            $table->dateTime('date_affectation');

            $table->text('motif')->nullable();

            $table->timestamps();

            $table->foreign('support_ticket_id')
                ->references('id')
                ->on('support_tickets')
                ->restrictOnDelete();

            $table->foreign('ancien_user_assigne_id')
                ->references('id')
                ->on('users')
                ->nullOnDelete();

            $table->foreign('nouveau_user_assigne_id')
                ->references('id')
                ->on('users')
                ->restrictOnDelete();

            $table->foreign('affecte_par_user_id')
                ->references('id')
                ->on('users')
                ->restrictOnDelete();

            $table->index(
                ['support_ticket_id', 'date_affectation'],
                'support_affectations_ticket_date_index'
            );

            $table->index('nouveau_user_assigne_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('support_ticket_affectations');
    }
};
