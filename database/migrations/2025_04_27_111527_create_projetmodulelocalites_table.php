<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProjetmodulelocalitesTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('projetmodulelocalites', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('projetmodules_id');
            $table->unsignedBigInteger('projetlocalites_id');
            $table->timestamps();

            // Clés étrangères
            $table->foreign('projetmodules_id')
                  ->references('id')->on('projetmodules')
                  ->onDelete('cascade');

            $table->foreign('projetlocalites_id')
                  ->references('id')->on('projetlocalites')
                  ->onDelete('cascade');

            // Empêcher les doublons
            $table->unique(['projetmodules_id', 'projetlocalites_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('projetmodulelocalites');
    }
}

