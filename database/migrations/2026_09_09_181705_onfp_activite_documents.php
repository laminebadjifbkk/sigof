<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('onfp_activite_documents', function (Blueprint $table) {
            $table->id();

            /* $table->foreignId('activite_id')
                ->constrained('onfp_activites')
                ->restrictOnDelete();

            $table->foreignId('tache_id')
                ->nullable()
                ->constrained('onfp_taches')
                ->restrictOnDelete();

            $table->foreignId('employee_id')
                ->nullable()
                ->constrained('employees')
                ->restrictOnDelete(); */
            $table->foreignId('activite_id')
                ->constrained('onfp_activites')
                ->restrictOnDelete();

            $table->foreignId('tache_id')
                ->nullable()
                ->constrained('onfp_taches')
                ->restrictOnDelete();

            $table->unsignedInteger('employee_id')->nullable();

            $table->foreign('employee_id')
                ->references('id')
                ->on('employees')
                ->restrictOnDelete();

            $table->string('type', 50)->nullable();

            $table->string('nom_original', 255);
            $table->string('nom_fichier', 255);

            $table->string('disk', 50)->default('public');
            $table->string('chemin', 500);

            $table->string('mime_type', 150)->nullable();
            $table->unsignedBigInteger('taille')->nullable();

            $table->text('description')->nullable();

            $table->boolean('document_final')->default(false);

            $table->timestamps();
            $table->softDeletes();

            $table->index(['activite_id', 'type']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('onfp_activite_documents');
    }
};
