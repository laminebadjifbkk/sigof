<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('support_types', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('support_category_id');

            $table->string('libelle', 150);
            $table->text('description')->nullable();

            $table->boolean('actif')->default(true);
            $table->unsignedInteger('ordre')->default(0);

            $table->unsignedInteger('user_create_id')->nullable();
            $table->unsignedInteger('user_update_id')->nullable();

            $table->timestamps();
            $table->softDeletes();

            $table->foreign('support_category_id')
                ->references('id')
                ->on('support_categories')
                ->restrictOnDelete();

            $table->foreign('user_create_id')
                ->references('id')
                ->on('users')
                ->nullOnDelete();

            $table->foreign('user_update_id')
                ->references('id')
                ->on('users')
                ->nullOnDelete();

            $table->unique(
                ['support_category_id', 'libelle'],
                'support_types_category_libelle_unique'
            );

            $table->index(
                ['support_category_id', 'actif', 'ordre'],
                'support_types_category_actif_ordre_index'
            );
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('support_types');
    }
};
