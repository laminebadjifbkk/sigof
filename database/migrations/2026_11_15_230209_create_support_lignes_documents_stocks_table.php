<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('support_lignes_documents_stocks', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('support_document_stock_id');
            $table->unsignedBigInteger('support_article_stock_id');

            $table->unsignedBigInteger('support_ligne_origine_id')->nullable();

            $table->decimal('quantite', 14, 3);
            $table->decimal('cout_unitaire', 18, 4)->default(0);
            $table->decimal('valeur_ligne', 18, 4)->default(0);

            $table->text('observations')->nullable();

            $table->timestamps();

            $table->foreign('support_document_stock_id', 'fk_sld_document')
    ->references('id')->on('support_documents_stocks')
    ->restrictOnDelete();

$table->foreign('support_article_stock_id', 'fk_sld_article')
    ->references('id')->on('support_articles_stocks')
    ->restrictOnDelete();

$table->foreign('support_ligne_origine_id', 'fk_sld_origine')
    ->references('id')->on('support_lignes_documents_stocks')
    ->restrictOnDelete();

            $table->index(
                ['support_document_stock_id', 'support_article_stock_id'],
                'support_lignes_document_article_index'
            );

            $table->index('support_ligne_origine_id', 'sld_origine_idx');
$table->index('support_article_stock_id', 'sld_article_idx');
        });

        DB::statement("
            ALTER TABLE support_lignes_documents_stocks
            ADD CONSTRAINT chk_support_ligne_quantite
            CHECK (quantite > 0)
        ");

        DB::statement("
            ALTER TABLE support_lignes_documents_stocks
            ADD CONSTRAINT chk_support_ligne_cout
            CHECK (cout_unitaire >= 0)
        ");

        DB::statement("
            ALTER TABLE support_lignes_documents_stocks
            ADD CONSTRAINT chk_support_ligne_valeur
            CHECK (valeur_ligne >= 0)
        ");
    }

    public function down(): void
    {
        Schema::dropIfExists('support_lignes_documents_stocks');
    }
};
