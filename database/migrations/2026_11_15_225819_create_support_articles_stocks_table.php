<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('support_articles_stocks', function (Blueprint $table) {
            $table->id();

            $table->string('reference', 40)->unique();
            $table->string('designation', 200);

            $table->unsignedBigInteger('support_categorie_stock_id');
            $table->unsignedBigInteger('support_unite_stock_id');

            $table->text('description')->nullable();

            $table->decimal('stock_minimum', 14, 3)->default(0);
            $table->decimal('stock_maximum', 14, 3)->nullable();

            $table->boolean('actif')->default(true);

            $table->unsignedInteger('user_create_id')->nullable();
            $table->unsignedInteger('user_update_id')->nullable();

            $table->timestamps();
            $table->softDeletes();

            $table->foreign('support_categorie_stock_id')
                ->references('id')->on('support_categories_stocks')
                ->restrictOnDelete();

            $table->foreign('support_unite_stock_id')
                ->references('id')->on('support_unites_stocks')
                ->restrictOnDelete();

            $table->foreign('user_create_id')
                ->references('id')->on('users')
                ->nullOnDelete();

            $table->foreign('user_update_id')
                ->references('id')->on('users')
                ->nullOnDelete();

            $table->index(['support_categorie_stock_id', 'actif']);
            $table->index(['support_unite_stock_id', 'actif']);
            $table->index('designation');
        });

        DB::statement("
            ALTER TABLE support_articles_stocks
            ADD CONSTRAINT chk_support_article_stock_minimum
            CHECK (stock_minimum >= 0)
        ");

        DB::statement("
            ALTER TABLE support_articles_stocks
            ADD CONSTRAINT chk_support_article_stock_maximum
            CHECK (
                stock_maximum IS NULL
                OR stock_maximum >= stock_minimum
            )
        ");
    }

    public function down(): void
    {
        Schema::dropIfExists('support_articles_stocks');
    }
};
