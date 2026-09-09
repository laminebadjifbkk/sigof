<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('onfp_taches', function (Blueprint $table) {
            $table->id();

            $table->foreignId('activite_id')
                ->nullable()
                ->constrained('onfp_activites')
                ->restrictOnDelete();

            $table->foreignId('sous_activite_id')
                ->nullable()
                ->constrained('onfp_sous_activites')
                ->restrictOnDelete();

            $table->string('reference', 50)->unique();

            $table->string('titre', 255);
            $table->text('description')->nullable();

            $table->string('statut', 50)->default('a_faire');
            $table->string('priorite', 30)->default('normale');

            $table->unsignedTinyInteger('progression')->default(0);

            $table->date('date_debut')->nullable();
            $table->date('date_echeance')->nullable();
            $table->date('date_realisation')->nullable();

            $table->text('observation')->nullable();

            /* $table->foreignId('created_by')
                ->nullable()
                ->constrained('employees')
                ->restrictOnDelete();

            $table->foreignId('updated_by')
                ->nullable()
                ->constrained('employees')
                ->restrictOnDelete(); */

            $table->unsignedInteger('created_by')->nullable();
            $table->unsignedInteger('updated_by')->nullable();

            $table->foreign('created_by')
                ->references('id')
                ->on('employees')
                ->restrictOnDelete();

            $table->foreign('updated_by')
                ->references('id')
                ->on('employees')
                ->restrictOnDelete();

            $table->timestamps();
            $table->softDeletes();

            $table->index(['activite_id', 'statut']);
            $table->index(['sous_activite_id', 'statut']);
            $table->index(['date_echeance']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('onfp_taches');
    }
};
