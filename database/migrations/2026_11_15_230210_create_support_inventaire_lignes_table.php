<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('support_inventaire_lignes', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('support_inventaire_id');
            $table->unsignedBigInteger('support_article_stock_id');

            $table->decimal('quantite_theorique', 14, 3);
            $table->decimal('quantite_comptee', 14, 3)->nullable();
            $table->decimal('ecart', 14, 3)->nullable();

            $table->unsignedInteger('user_comptage_id')->nullable();
            $table->dateTime('date_comptage')->nullable();

            $table->text('observations')->nullable();

            $table->timestamps();

            $table->foreign('support_inventaire_id', 'fk_sil_inventaire')
    ->references('id')->on('support_inventaires_stocks')
    ->restrictOnDelete();

$table->foreign('support_article_stock_id', 'fk_sil_article')
    ->references('id')->on('support_articles_stocks')
    ->restrictOnDelete();

$table->foreign('user_comptage_id', 'fk_sil_user')
    ->references('id')->on('users')
    ->nullOnDelete();

            $table->unique(
                ['support_inventaire_id', 'support_article_stock_id'],
                'support_inventaire_article_unique'
            );

            $table->index('support_article_stock_id', 'sil_article_idx');
        });

        DB::statement("
            ALTER TABLE support_inventaire_lignes
            ADD CONSTRAINT chk_support_inventaire_qte_theorique
            CHECK (quantite_theorique >= 0)
        ");

        DB::statement("
            ALTER TABLE support_inventaire_lignes
            ADD CONSTRAINT chk_support_inventaire_qte_comptee
            CHECK (
                quantite_comptee IS NULL
                OR quantite_comptee >= 0
            )
        ");

        DB::statement("
            ALTER TABLE support_inventaire_lignes
            ADD CONSTRAINT chk_support_inventaire_ecart
            CHECK (
                (
                    quantite_comptee IS NULL
                    AND ecart IS NULL
                )
                OR
                (
                    quantite_comptee IS NOT NULL
                    AND ecart IS NOT NULL
                    AND ecart = quantite_comptee - quantite_theorique
                )
            )
        ");
    }

    public function down(): void
    {
        Schema::dropIfExists('support_inventaire_lignes');
    }
};
