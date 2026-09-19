<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('onfp_activite_commentaires', function (Blueprint $table) {
            // Nullable : un commentaire peut être sur l'activité en général
            // (tache_id = null), ou ciblé sur une tâche précise de cette
            // activité (activite_id reste renseigné dans les deux cas,
            // même pattern que onfp_taches.sous_activite_id).
            $table->foreignId('tache_id')
                ->nullable()
                ->after('activite_id')
                ->constrained('onfp_taches')
                ->cascadeOnDelete();

            $table->index(['tache_id', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::table('onfp_activite_commentaires', function (Blueprint $table) {
            $table->dropForeign(['tache_id']);
            $table->dropIndex(['tache_id', 'created_at']);
            $table->dropColumn('tache_id');
        });
    }
};
