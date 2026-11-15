<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('support_mouvements_equipements', function (Blueprint $table) {
            $table->id();

            $table->foreignId('support_equipement_id');

            $table->foreignId('affectation_origine_id')->nullable();

            $table->foreignId('affectation_destination_id')->nullable();

            $table->string('type_mouvement', 40);
            $table->dateTime('date_mouvement');

            $table->string('motif', 255)->nullable();
            $table->text('observations')->nullable();

            $table->unsignedInteger('user_create_id')->nullable();

            $table->timestamps();

            // Clés étrangères avec noms explicites et courts
            $table->foreign('support_equipement_id', 'fk_sme_equipement')
                ->references('id')
                ->on('support_equipements')
                ->restrictOnDelete();

            $table->foreign('affectation_origine_id', 'fk_sme_affect_orig')
                ->references('id')
                ->on('support_affectations_equipements')
                ->restrictOnDelete();

            $table->foreign('affectation_destination_id', 'fk_sme_affect_dest')
                ->references('id')
                ->on('support_affectations_equipements')
                ->restrictOnDelete();

            $table->foreign('user_create_id', 'fk_sme_user')
                ->references('id')
                ->on('users')
                ->nullOnDelete();

            $table->index(
                ['support_equipement_id', 'date_mouvement'],
                'idx_mouv_equip_date'
            );

            $table->index('type_mouvement');
        });

        DB::statement("
            ALTER TABLE support_mouvements_equipements
            ADD CONSTRAINT chk_mouvement_equipement_affectations
            CHECK (
                (type_mouvement = 'AFFECTATION'
                    AND affectation_origine_id IS NULL
                    AND affectation_destination_id IS NOT NULL)
                OR
                (type_mouvement = 'RETOUR'
                    AND affectation_origine_id IS NOT NULL
                    AND affectation_destination_id IS NULL)
                OR
                (type_mouvement = 'TRANSFERT'
                    AND affectation_origine_id IS NOT NULL
                    AND affectation_destination_id IS NOT NULL)
            )
        ");
    }

    public function down(): void
    {
        Schema::dropIfExists('support_mouvements_equipements');
    }
};
