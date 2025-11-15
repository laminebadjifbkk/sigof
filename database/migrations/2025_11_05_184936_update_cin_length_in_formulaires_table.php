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
        Schema::table('formulaires', function (Blueprint $table) {
            $table->string('cin', 20)->change(); // augmente la taille à 20 caractères
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('formulaires', function (Blueprint $table) {
            $table->string('cin', 10)->change(); // remettre l'ancienne taille
        });
    }
};
