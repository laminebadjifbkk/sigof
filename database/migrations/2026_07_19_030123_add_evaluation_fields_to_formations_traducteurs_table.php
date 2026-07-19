<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('formations_traducteurs', function (Blueprint $table) {
            $table->decimal('note_evaluation', 4, 2)->nullable()->after('statut_formation');
            $table->enum('resultat_evaluation', ['reussi', 'echoue', 'rattrapage'])->nullable()->after('note_evaluation');
            $table->text('commentaire_evaluation')->nullable()->after('resultat_evaluation');
            $table->date('date_evaluation')->nullable()->after('commentaire_evaluation');
            $table->unsignedInteger('evalue_par')->nullable()->after('date_evaluation');
            $table->foreign('evalue_par')->references('id')->on('users')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('formations_traducteurs', function (Blueprint $table) {
            $table->dropForeign(['evalue_par']);
            $table->dropColumn(['note_evaluation', 'resultat_evaluation', 'commentaire_evaluation', 'date_evaluation', 'evalue_par']);
        });
    }
};
