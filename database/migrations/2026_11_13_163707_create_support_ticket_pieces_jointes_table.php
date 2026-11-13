<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('support_ticket_pieces_jointes', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('support_ticket_id');
            $table->unsignedInteger('user_id');

            $table->string('nom_original', 255);
            $table->string('nom_stockage', 255);
            $table->string('disque', 50)->default('local');
            $table->string('chemin', 500);

            $table->string('mime_type', 150)->nullable();
            $table->unsignedBigInteger('taille_octets')->default(0);

            $table->timestamps();
            $table->softDeletes();

            $table->foreign('support_ticket_id')
                ->references('id')->on('support_tickets')
                ->restrictOnDelete();

            $table->foreign('user_id')
                ->references('id')->on('users')
                ->restrictOnDelete();

            $table->index(
                ['support_ticket_id', 'created_at'],
                'support_ticket_pieces_jointes_ticket_date_index'
            );
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('support_ticket_pieces_jointes');
    }
};
