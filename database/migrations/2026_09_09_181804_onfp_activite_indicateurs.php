<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('onfp_activite_indicateurs', function (Blueprint $table) {
            $table->id();

            $table->foreignId('activite_id')
                ->constrained('onfp_activites')
                ->restrictOnDelete();

            $table->string('code', 100)->nullable();

            $table->string('libelle', 255);
            $table->text('description')->nullable();

            $table->string('unite', 100)->nullable();

            $table->decimal('valeur_cible', 15, 2)->nullable();
            $table->decimal('valeur_realisee', 15, 2)->default(0);

            $table->decimal('pourcentage', 8, 2)->default(0);

            $table->date('date_reference')->nullable();

            $table->string('sens', 30)->default('croissant');

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

            $table->index(['activite_id', 'date_reference']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('onfp_activite_indicateurs');
    }
};
