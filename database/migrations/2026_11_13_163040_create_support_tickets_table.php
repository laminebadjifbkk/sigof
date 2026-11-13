<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('support_tickets', function (Blueprint $table) {
            $table->id();

            $table->string('reference', 30)->unique();
            $table->string('objet', 200);
            $table->longText('description');

            $table->unsignedBigInteger('support_category_id');
            $table->unsignedBigInteger('support_type_id');
            $table->unsignedBigInteger('support_priorite_id');
            $table->unsignedBigInteger('support_statut_id');

            $table->unsignedInteger('user_demandeur_id');
            $table->unsignedInteger('user_assigne_id')->nullable();

            $table->dateTime('date_soumission');
            $table->dateTime('date_prise_en_charge')->nullable();
            $table->dateTime('date_resolution')->nullable();
            $table->dateTime('date_cloture')->nullable();

            $table->text('motif_rejet')->nullable();
            $table->text('motif_attente')->nullable();

            $table->timestamps();
            $table->softDeletes();

            $table->foreign('support_category_id')
                ->references('id')->on('support_categories')
                ->restrictOnDelete();

            $table->foreign('support_type_id')
                ->references('id')->on('support_types')
                ->restrictOnDelete();

            $table->foreign('support_priorite_id')
                ->references('id')->on('support_priorites')
                ->restrictOnDelete();

            $table->foreign('support_statut_id')
                ->references('id')->on('support_statuts')
                ->restrictOnDelete();

            $table->foreign('user_demandeur_id')
                ->references('id')->on('users')
                ->restrictOnDelete();

            $table->foreign('user_assigne_id')
                ->references('id')->on('users')
                ->nullOnDelete();

            $table->index(
                ['support_statut_id', 'support_priorite_id'],
                'support_tickets_statut_priorite_index'
            );

            $table->index(
                ['user_demandeur_id', 'date_soumission'],
                'support_tickets_demandeur_date_index'
            );

            $table->index(
                ['user_assigne_id', 'support_statut_id'],
                'support_tickets_assigne_statut_index'
            );
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('support_tickets');
    }
};
