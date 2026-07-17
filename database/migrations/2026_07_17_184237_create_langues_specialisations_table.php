<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('langues_specialisations', function (Blueprint $table) {
            $table->id();
            $table->string('nom');                        // Espagnol, Arabe, Anglais (bilingue)...
            $table->string('code')->unique();              // espagnol, arabe, anglais_bilingue...
            $table->unsignedSmallInteger('postes_disponibles');
            $table->string('niveau_langue_requis', 10);     // C1
            $table->string('niveau_francais_requis', 10);   // C1 ou C1/B2
            $table->string('diplome_minimum');              // "Licence / Master ou niveau équivalent"
            $table->string('certification_recommandee')->nullable(); // DELE, HSK 5+, TOEIC...
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('langues_specialisation');
    }
};