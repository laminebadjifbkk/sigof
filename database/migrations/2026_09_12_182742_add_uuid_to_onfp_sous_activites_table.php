<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Ajouter la colonne UUID
        Schema::table('onfp_sous_activites', function (Blueprint $table) {
            $table->uuid('uuid')
                ->nullable()
                ->after('id');
        });

        // 2. Générer un UUID pour les sous-activités existantes
        DB::table('onfp_sous_activites')
            ->whereNull('uuid')
            ->orderBy('id')
            ->chunkById(100, function ($sousActivites) {
                foreach ($sousActivites as $sousActivite) {
                    DB::table('onfp_sous_activites')
                        ->where('id', $sousActivite->id)
                        ->update([
                            'uuid' => (string) Str::uuid(),
                        ]);
                }
            });

        // 3. Rendre UUID obligatoire
        Schema::table('onfp_sous_activites', function (Blueprint $table) {
            $table->uuid('uuid')
                ->nullable(false)
                ->change();
        });

        // 4. Garantir l'unicité
        Schema::table('onfp_sous_activites', function (Blueprint $table) {
            $table->unique('uuid');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('onfp_sous_activites', function (Blueprint $table) {
            $table->dropUnique(['uuid']);
            $table->dropColumn('uuid');
        });
    }
};
