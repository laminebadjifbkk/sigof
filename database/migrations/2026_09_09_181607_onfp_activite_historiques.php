<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('onfp_activite_historiques', function (Blueprint $table) {
            $table->id();

            /* $table->foreignId('activite_id')
                ->constrained('onfp_activites')
                ->restrictOnDelete();

            $table->foreignId('employee_id')
                ->nullable()
                ->constrained('employees')
                ->restrictOnDelete(); */

            $table->foreignId('activite_id')
                ->constrained('onfp_activites')
                ->restrictOnDelete();

            $table->unsignedInteger('employee_id')->nullable();

            $table->foreign('employee_id')
                ->references('id')
                ->on('employees')
                ->restrictOnDelete();

            $table->string('action', 100);

            $table->string('ancien_statut', 50)->nullable();
            $table->string('nouveau_statut', 50)->nullable();

            $table->unsignedTinyInteger('ancienne_progression')->nullable();
            $table->unsignedTinyInteger('nouvelle_progression')->nullable();

            $table->text('description')->nullable();

            $table->json('donnees_avant')->nullable();
            $table->json('donnees_apres')->nullable();

            $table->timestamps();

            $table->index(['activite_id', 'created_at']);
            $table->index(['employee_id', 'created_at']);
            $table->index(['action']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('onfp_activite_historiques');
    }
};
