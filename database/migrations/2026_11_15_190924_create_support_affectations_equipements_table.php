<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('support_affectations_equipements', function (Blueprint $table) {
            $table->id();

            $table->foreignId('support_equipement_id')
                ->constrained('support_equipements')
                ->restrictOnDelete();

            $table->unsignedInteger('employee_id')->nullable();
            $table->unsignedInteger('direction_id')->nullable();
            $table->unsignedInteger('antenne_id')->nullable();

            $table->dateTime('date_debut');
            $table->dateTime('date_fin')->nullable();

            $table->string('etat_remise', 30)->nullable();
            $table->string('etat_retour', 30)->nullable();

            $table->text('observations')->nullable();
            $table->text('motif_retour')->nullable();

            $table->unsignedInteger('user_create_id')->nullable();
            $table->unsignedInteger('user_update_id')->nullable();

            $table->timestamps();

            $table->foreign('employee_id')
                ->references('id')->on('employees')
                ->restrictOnDelete();

            $table->foreign('direction_id')
                ->references('id')->on('directions')
                ->restrictOnDelete();

            $table->foreign('antenne_id')
                ->references('id')->on('antennes')
                ->restrictOnDelete();

            $table->foreign('user_create_id')
                ->references('id')->on('users')
                ->nullOnDelete();

            $table->foreign('user_update_id')
                ->references('id')->on('users')
                ->nullOnDelete();

            $table->index(['support_equipement_id', 'date_fin'], 'idx_affect_equip_date_fin');
            $table->index(['employee_id', 'date_fin'], 'idx_affect_employee_date_fin');
            $table->index(['direction_id', 'date_fin'], 'idx_affect_direction_date_fin');
            $table->index(['antenne_id', 'date_fin'], 'idx_affect_antenne_date_fin');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('support_affectations_equipements');
    }
};
