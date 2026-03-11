<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('activitequotidiennes', function (Blueprint $table) {

            $table->id();

            $table->string('titre');
            $table->text('description')->nullable();

            $table->unsignedInteger('user_id')->nullable();

            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->nullOnDelete();

            $table->date('date_activite');

            $table->enum('statut', [
                'en_attente',
                'en_cours',
                'terminee',
                'validee',
                'rejete'
            ])->default('en_attente');

            $table->enum('priorite', ['faible', 'normale', 'urgente'])->default('normale');

            $table->boolean('alerte_envoyee')->default(false);

            $table->time('heure_debut')->nullable();
            $table->time('heure_fin')->nullable();

            $table->unsignedInteger('validateur_id')->nullable();

            $table->foreign('validateur_id')
                ->references('id')
                ->on('users')
                ->nullOnDelete();

            $table->timestamp('date_validation')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('activitequotidiennes');
    }
};
