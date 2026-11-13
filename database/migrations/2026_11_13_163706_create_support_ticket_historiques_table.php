<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('support_ticket_historiques', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('support_ticket_id');
            $table->unsignedInteger('user_id')->nullable();

            $table->string('action', 100);
            $table->string('champ', 100)->nullable();
            $table->text('ancienne_valeur')->nullable();
            $table->text('nouvelle_valeur')->nullable();
            $table->text('description')->nullable();

            $table->timestamps();

            $table->foreign('support_ticket_id')
                ->references('id')->on('support_tickets')
                ->restrictOnDelete();

            $table->foreign('user_id')
                ->references('id')->on('users')
                ->nullOnDelete();

            $table->index(
                ['support_ticket_id', 'created_at'],
                'support_ticket_historiques_ticket_date_index'
            );
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('support_ticket_historiques');
    }
};
