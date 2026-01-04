<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Transformation de la colonne statut en string
        Schema::table('parc_missions', function (Blueprint $table) {
            $table->string('statut', 50)->default('planifiee')->change();
        });
    }

    public function down(): void
    {
        // Revenir à l'enum d'origine si rollback
        Schema::table('parc_missions', function (Blueprint $table) {
            $table->enum('statut', ['planifiee', 'en_cours', 'cloturee', 'annulee'])
                  ->default('planifiee')
                  ->change();
        });
    }
};

