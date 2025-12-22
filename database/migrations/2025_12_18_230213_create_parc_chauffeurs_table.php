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
        Schema::create('parc_chauffeurs', function (Blueprint $table) {
            $table->id();
            // user_id en INT UNSIGNED
            $table->unsignedInteger('user_id')->nullable();
            $table->foreign('user_id')->references('id')->on('users')->cascadeOnDelete();

            // employee_id en INT UNSIGNED
            $table->unsignedInteger('employee_id')->nullable();
            $table->foreign('employee_id')->references('id')->on('employees')->cascadeOnDelete();

            $table->string('matricule')->unique();
            $table->string('nom');
            $table->string('prenom')->nullable();
            $table->string('telephone')->nullable();
            $table->enum('statut', ['actif', 'indisponible', 'archive'])->default('actif');
            $table->string('permis_numero')->nullable();
            $table->string('permis_categories')->nullable();
            $table->date('permis_expire_le')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('parc_chauffeurs');
    }
};
