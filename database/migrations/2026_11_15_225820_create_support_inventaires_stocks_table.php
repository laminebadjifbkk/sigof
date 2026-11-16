<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('support_inventaires_stocks', function (Blueprint $table) {
            $table->id();

            $table->string('reference', 40)->unique();
            $table->string('libelle', 200);
            $table->text('description')->nullable();

            $table->dateTime('date_debut');
            $table->dateTime('date_fin')->nullable();

            $table->string('statut', 30)->default('BROUILLON');

            $table->unsignedInteger('user_create_id');
            $table->unsignedInteger('user_validation_id')->nullable();

            $table->dateTime('date_soumission')->nullable();
            $table->dateTime('date_validation')->nullable();

            $table->text('motif_rejet')->nullable();
            $table->text('observations')->nullable();

            $table->timestamps();

            $table->foreign('user_create_id')
                ->references('id')->on('users')
                ->restrictOnDelete();

            $table->foreign('user_validation_id')
                ->references('id')->on('users')
                ->nullOnDelete();

            $table->index(['statut', 'date_debut']);
            $table->index('user_create_id');
            $table->index('date_validation');
        });

        DB::statement("
            ALTER TABLE support_inventaires_stocks
            ADD CONSTRAINT chk_support_inventaire_statut
            CHECK (
                statut IN (
                    'BROUILLON',
                    'EN_COURS',
                    'EN_ATTENTE_VALIDATION',
                    'VALIDEE',
                    'REJETEE',
                    'ANNULEE'
                )
            )
        ");

        DB::statement("
            ALTER TABLE support_inventaires_stocks
            ADD CONSTRAINT chk_support_inventaire_dates
            CHECK (
                date_fin IS NULL
                OR date_fin >= date_debut
            )
        ");
    }

    public function down(): void
    {
        Schema::dropIfExists('support_inventaires_stocks');
    }
};
