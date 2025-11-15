<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('lettrevaluations', function (Blueprint $table) {
            $table->engine = 'InnoDB';

            $table->id();
            $table->uuid('uuid')->unique();

            $table->string('titre')->nullable();
            $table->text('contenu')->nullable();

            $table->unsignedBigInteger('users_id')->nullable()->comment('Utilisateur ayant rédigé la lettre');
            $table->unsignedBigInteger('formations_id')->nullable()->comment('Formation concernée');
            $table->unsignedBigInteger('operateurs_id')->nullable()->comment('Opérateur concerné');
            $table->unsignedBigInteger('onfpevaluateurs_id')->nullable()->comment('Évaluateur ONFP');
            $table->unsignedBigInteger('evaluateurs_id')->nullable()->comment('Autre évaluateur');

            $table->softDeletes();
            $table->nullableTimestamps();

            // Index utiles
            $table->index(['users_id', 'formations_id']);
            $table->index('uuid');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('lettrevaluations');
    }
};
