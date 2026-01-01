<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // 🔍 Supprimer FK vehicule_id si elle existe
        $this->dropForeignIfExists('parc_missions', 'vehicule_id');

        // 🔍 Supprimer FK chauffeur_id si elle existe
        $this->dropForeignIfExists('parc_missions', 'chauffeur_id');

        Schema::table('parc_missions', function (Blueprint $table) {
            $table->unsignedBigInteger('vehicule_id')->nullable()->change();
            $table->unsignedBigInteger('chauffeur_id')->nullable()->change();

            $table->foreign('vehicule_id')
                ->references('id')
                ->on('parc_vehicules')
                ->nullOnDelete();

            $table->foreign('chauffeur_id')
                ->references('id')
                ->on('parc_chauffeurs')
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        $this->dropForeignIfExists('parc_missions', 'vehicule_id');
        $this->dropForeignIfExists('parc_missions', 'chauffeur_id');

        Schema::table('parc_missions', function (Blueprint $table) {
            $table->unsignedBigInteger('vehicule_id')->nullable(false)->change();
            $table->unsignedBigInteger('chauffeur_id')->nullable(false)->change();

            $table->foreign('vehicule_id')
                ->references('id')
                ->on('parc_vehicules')
                ->cascadeOnDelete();

            $table->foreign('chauffeur_id')
                ->references('id')
                ->on('parc_chauffeurs')
                ->cascadeOnDelete();
        });
    }

    /**
     * Supprime une clé étrangère si elle existe
     */
    private function dropForeignIfExists(string $table, string $column): void
    {
        $foreignKeys = DB::select("
            SELECT CONSTRAINT_NAME
            FROM information_schema.KEY_COLUMN_USAGE
            WHERE TABLE_SCHEMA = DATABASE()
              AND TABLE_NAME = ?
              AND COLUMN_NAME = ?
              AND REFERENCED_TABLE_NAME IS NOT NULL
        ", [$table, $column]);

        foreach ($foreignKeys as $fk) {
            DB::statement("ALTER TABLE {$table} DROP FOREIGN KEY {$fk->CONSTRAINT_NAME}");
        }
    }
};
