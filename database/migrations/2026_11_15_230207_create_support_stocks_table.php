<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('support_stocks', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('support_article_stock_id')->unique();

            $table->decimal('quantite', 14, 3)->default(0);
            $table->decimal('cout_moyen_pondere', 18, 4)->default(0);
            $table->decimal('valeur_stock', 18, 4)->default(0);

            $table->boolean('verrouille')->default(false);
            $table->unsignedBigInteger('support_inventaire_id')->nullable();

            $table->timestamps();

            $table->foreign('support_article_stock_id')
                ->references('id')->on('support_articles_stocks')
                ->restrictOnDelete();

            $table->foreign('support_inventaire_id')
                ->references('id')->on('support_inventaires_stocks')
                ->restrictOnDelete();

            $table->index(['verrouille', 'support_inventaire_id']);
        });

        DB::statement("
            ALTER TABLE support_stocks
            ADD CONSTRAINT chk_support_stocks_quantite
            CHECK (quantite >= 0)
        ");

        DB::statement("
            ALTER TABLE support_stocks
            ADD CONSTRAINT chk_support_stocks_cmp
            CHECK (cout_moyen_pondere >= 0)
        ");

        DB::statement("
            ALTER TABLE support_stocks
            ADD CONSTRAINT chk_support_stocks_valeur
            CHECK (valeur_stock >= 0)
        ");

        DB::statement("
            ALTER TABLE support_stocks
            ADD CONSTRAINT chk_support_stocks_verrou
            CHECK (
                (verrouille = 1 AND support_inventaire_id IS NOT NULL)
                OR
                (verrouille = 0 AND support_inventaire_id IS NULL)
            )
        ");
    }

    public function down(): void
    {
        Schema::dropIfExists('support_stocks');
    }
};
