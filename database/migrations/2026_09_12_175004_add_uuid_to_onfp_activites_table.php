<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    public function up(): void
    {
        /*
         * 1. Ajouter temporairement la colonne UUID nullable
         */
        Schema::table('onfp_activites', function (Blueprint $table) {
            $table->uuid('uuid')
                ->nullable()
                ->after('id');
        });

        /*
         * 2. Générer un UUID pour les activités existantes
         */
        DB::table('onfp_activites')
            ->whereNull('uuid')
            ->orderBy('id')
            ->chunkById(100, function ($activites) {

                foreach ($activites as $activite) {

                    DB::table('onfp_activites')
                        ->where('id', $activite->id)
                        ->update([
                            'uuid' => (string) Str::uuid(),
                        ]);
                }
            });

        /*
         * 3. Rendre UUID obligatoire
         */
        Schema::table('onfp_activites', function (Blueprint $table) {
            $table->uuid('uuid')
                ->nullable(false)
                ->change();
        });

        /*
         * 4. Garantir l'unicité
         */
        Schema::table('onfp_activites', function (Blueprint $table) {
            $table->unique('uuid');
        });
    }

    public function down(): void
    {
        Schema::table('onfp_activites', function (Blueprint $table) {
            $table->dropUnique(['uuid']);
            $table->dropColumn('uuid');
        });
    }
};
