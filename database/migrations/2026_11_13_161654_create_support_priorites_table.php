<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('support_priorites', function (Blueprint $table) {
            $table->id();

            $table->string('code', 10)->unique();
            $table->string('libelle', 100);
            $table->text('description')->nullable();

            // Délai cible de première prise en charge, en minutes.
            $table->unsignedInteger('delai_prise_en_charge_minutes')
                ->default(0);

            $table->boolean('actif')->default(true);
            $table->unsignedInteger('ordre')->default(0);

            // La clé primaire de users est un unsigned integer.
            $table->unsignedInteger('user_create_id')->nullable();
            $table->unsignedInteger('user_update_id')->nullable();

            $table->timestamps();
            $table->softDeletes();

            $table->foreign('user_create_id')
                ->references('id')
                ->on('users')
                ->nullOnDelete();

            $table->foreign('user_update_id')
                ->references('id')
                ->on('users')
                ->nullOnDelete();

            $table->index(
                ['actif', 'ordre'],
                'support_priorites_actif_ordre_index'
            );
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('support_priorites');
    }
};
