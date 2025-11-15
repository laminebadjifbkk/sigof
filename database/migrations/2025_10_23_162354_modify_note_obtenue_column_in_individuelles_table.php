<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('individuelles', function (Blueprint $table) {
            // On modifie le type de la colonne 'note_obtenue' pour qu'elle accepte des chaînes (string)
            $table->string('note_obtenue')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('individuelles', function (Blueprint $table) {
            // On remet le type d'origine (double) si besoin
            $table->double('note_obtenue')->nullable()->change();
        });
    }
};
