<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('support_ticket_commentaires', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('support_ticket_id');
            $table->unsignedInteger('user_id');

            $table->longText('contenu');

            // true : visible par le demandeur.
            // false : échange interne au support.
            $table->boolean('visible_demandeur')->default(true);

            $table->timestamps();
            $table->softDeletes();

            $table->foreign('support_ticket_id')
                ->references('id')->on('support_tickets')
                ->restrictOnDelete();

            $table->foreign('user_id')
                ->references('id')->on('users')
                ->restrictOnDelete();

            $table->index(
                ['support_ticket_id', 'visible_demandeur', 'created_at'],
                'support_ticket_commentaires_visibilite_index'
            );
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('support_ticket_commentaires');
    }
};
