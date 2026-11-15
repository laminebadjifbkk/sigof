<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('support_equipement_historiques', function (Blueprint $table) {
            $table->id();

            $table->foreignId('support_equipement_id')
                ->constrained('support_equipements')
                ->restrictOnDelete();

            $table->unsignedInteger('user_id')->nullable();

            $table->string('action', 50);
            $table->string('champ', 100)->nullable();

            $table->longText('ancienne_valeur')->nullable();
            $table->longText('nouvelle_valeur')->nullable();
            $table->text('description')->nullable();

            $table->timestamps();

            $table->foreign('user_id')
                ->references('id')->on('users')
                ->nullOnDelete();

            $table->index(
                ['support_equipement_id', 'created_at'],
                'idx_hist_equipement_created'
            );

            $table->index('action');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('support_equipement_historiques');
    }
};
