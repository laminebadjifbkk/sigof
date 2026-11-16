<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('support_mouvements_stocks', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('support_document_stock_id');
            $table->unsignedBigInteger('support_ligne_document_stock_id');
            $table->unsignedBigInteger('support_article_stock_id');

            $table->string('type_mouvement', 30);

            $table->decimal('quantite', 14, 3);
            $table->decimal('cout_unitaire', 18, 4);
            $table->decimal('valeur_mouvement', 18, 4);

            $table->decimal('quantite_avant', 14, 3);
            $table->decimal('quantite_apres', 14, 3);

            $table->unsignedInteger('user_create_id');
            $table->dateTime('date_mouvement');

            $table->timestamps();

            $table->foreign('support_document_stock_id', 'fk_sm_document')
    ->references('id')->on('support_documents_stocks')
    ->restrictOnDelete();

$table->foreign('support_ligne_document_stock_id', 'fk_sm_ligne')
    ->references('id')->on('support_lignes_documents_stocks')
    ->restrictOnDelete();

$table->foreign('support_article_stock_id', 'fk_sm_article')
    ->references('id')->on('support_articles_stocks')
    ->restrictOnDelete();

$table->foreign('user_create_id', 'fk_sm_user')
    ->references('id')->on('users')
    ->restrictOnDelete();

            $table->unique(
                'support_ligne_document_stock_id',
                'support_mouvement_ligne_unique'
            );

            $table->index(
    ['support_article_stock_id', 'date_mouvement'],
    'sm_article_date_idx'
);

$table->index(
    ['type_mouvement', 'date_mouvement'],
    'sm_type_date_idx'
);

$table->index('support_document_stock_id', 'sm_document_idx');
        });

        DB::statement("
            ALTER TABLE support_mouvements_stocks
            ADD CONSTRAINT chk_support_mouvement_type
            CHECK (
                type_mouvement IN (
                    'ENTREE',
                    'SORTIE',
                    'RETOUR',
                    'AJUSTEMENT_POSITIF',
                    'AJUSTEMENT_NEGATIF'
                )
            )
        ");

        DB::statement("
            ALTER TABLE support_mouvements_stocks
            ADD CONSTRAINT chk_support_mouvement_quantite
            CHECK (
                quantite > 0
                AND quantite_avant >= 0
                AND quantite_apres >= 0
            )
        ");

        DB::statement("
            ALTER TABLE support_mouvements_stocks
            ADD CONSTRAINT chk_support_mouvement_valeurs
            CHECK (
                cout_unitaire >= 0 AND valeur_mouvement >= 0
            )
        ");

        DB::statement("
            ALTER TABLE support_mouvements_stocks
            ADD CONSTRAINT chk_support_mouvement_coherence
            CHECK (
                (
                    type_mouvement IN ('ENTREE', 'RETOUR', 'AJUSTEMENT_POSITIF')
                    AND quantite_apres = quantite_avant + quantite
                )
                OR
                (
                    type_mouvement IN ('SORTIE', 'AJUSTEMENT_NEGATIF')
                    AND quantite_apres = quantite_avant - quantite
                )
            )
        ");
    }

    public function down(): void
    {
        Schema::dropIfExists('support_mouvements_stocks');
    }
};
