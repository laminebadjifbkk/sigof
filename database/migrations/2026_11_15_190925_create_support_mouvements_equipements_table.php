<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('support_mouvements_equipements', function (Blueprint $table) {
            $table->id();

            $table->foreignId('support_equipement_id')
                ->constrained('support_equipements')
                ->restrictOnDelete();

            $table->foreignId('affectation_origine_id')
                ->nullable()
                ->constrained('support_affectations_equipements')
                ->restrictOnDelete();

            $table->foreignId('affectation_destination_id')
                ->nullable()
                ->constrained('support_affectations_equipements')
                ->restrictOnDelete();

            $table->string('type_mouvement', 40);
            $table->dateTime('date_mouvement');

            $table->string('motif', 255)->nullable();
            $table->text('observations')->nullable();

            $table->unsignedInteger('user_create_id')->nullable();

            $table->timestamps();

            $table->foreign('user_create_id')
                ->references('id')->on('users')
                ->nullOnDelete();

            $table->index(['support_equipement_id', 'date_mouvement'], 'idx_mouv_equip_date');
            $table->index('type_mouvement');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('support_mouvements_equipements');
    }
};
