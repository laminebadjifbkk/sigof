<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('support_documents_stocks', function (Blueprint $table) {
            $table->id();

            $table->string('reference', 40)->unique();

            $table->string('type_document', 30);
            $table->string('statut', 30)->default('BROUILLON');

            $table->unsignedBigInteger('support_fournisseur_stock_id')->nullable();

            // Destinataire d'une sortie : un seul choix possible.
            $table->unsignedInteger('employee_id')->nullable();
            $table->unsignedInteger('direction_id')->nullable();
            $table->unsignedInteger('antenne_id')->nullable();
            $table->unsignedBigInteger('support_ticket_id')->nullable();

            $table->dateTime('date_document');
            $table->dateTime('date_soumission')->nullable();
            $table->dateTime('date_validation')->nullable();

            $table->unsignedInteger('user_create_id');
            $table->unsignedInteger('user_validation_id')->nullable();

            $table->string('motif', 255)->nullable();
            $table->text('observations')->nullable();
            $table->text('motif_rejet')->nullable();

            $table->timestamps();

            $table->foreign('support_fournisseur_stock_id')
                ->references('id')->on('support_fournisseurs_stocks')
                ->restrictOnDelete();

            $table->foreign('employee_id')
                ->references('id')->on('employees')
                ->restrictOnDelete();

            $table->foreign('direction_id')
                ->references('id')->on('directions')
                ->restrictOnDelete();

            $table->foreign('antenne_id')
                ->references('id')->on('antennes')
                ->restrictOnDelete();

            $table->foreign('support_ticket_id')
                ->references('id')->on('support_tickets')
                ->restrictOnDelete();

            $table->foreign('user_create_id')
                ->references('id')->on('users')
                ->restrictOnDelete();

            $table->foreign('user_validation_id')
                ->references('id')->on('users')
                ->nullOnDelete();

            $table->index(['type_document', 'statut']);
            $table->index(['date_document', 'type_document']);
            $table->index('user_create_id');
            $table->index('user_validation_id');
            $table->index('support_fournisseur_stock_id');
        });

        DB::statement("
            ALTER TABLE support_documents_stocks
            ADD CONSTRAINT chk_support_document_type
            CHECK (
                type_document IN (
                    'RECEPTION',
                    'SORTIE',
                    'RETOUR',
                    'AJUSTEMENT_POSITIF',
                    'AJUSTEMENT_NEGATIF'
                )
            )
        ");

        DB::statement("
            ALTER TABLE support_documents_stocks
            ADD CONSTRAINT chk_support_document_statut
            CHECK (
                statut IN (
                    'BROUILLON',
                    'EN_ATTENTE_VALIDATION',
                    'VALIDEE',
                    'REJETEE',
                    'ANNULEE'
                )
            )
        ");

        DB::statement("
            ALTER TABLE support_documents_stocks
            ADD CONSTRAINT chk_support_document_destinataire_hors_sortie
            CHECK (
                type_document = 'SORTIE'
                OR (
                    employee_id IS NULL
                    AND direction_id IS NULL
                    AND antenne_id IS NULL
                    AND support_ticket_id IS NULL
                )
            )
        ");

               DB::statement("
            ALTER TABLE support_documents_stocks
            ADD CONSTRAINT chk_support_document_sortie_un_destinataire
            CHECK (
                type_document <> 'SORTIE'
                OR (
                    (employee_id IS NOT NULL AND direction_id IS NULL AND antenne_id IS NULL AND support_ticket_id IS NULL)
                    OR (employee_id IS NULL AND direction_id IS NOT NULL AND antenne_id IS NULL AND support_ticket_id IS NULL)
                    OR (employee_id IS NULL AND direction_id IS NULL AND antenne_id IS NOT NULL AND support_ticket_id IS NULL)
                    OR (employee_id IS NULL AND direction_id IS NULL AND antenne_id IS NULL AND support_ticket_id IS NOT NULL)
                )
            )
        ");

        DB::statement("
            ALTER TABLE support_documents_stocks
            ADD CONSTRAINT chk_support_document_fournisseur
            CHECK (
                type_document <> 'RECEPTION'
                OR support_fournisseur_stock_id IS NOT NULL
            )
        ");
    }

    public function down(): void
    {
        Schema::dropIfExists('support_documents_stocks');
    }
};
