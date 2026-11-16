<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('support_fournisseurs_stocks', function (Blueprint $table) {
            $table->id();

            $table->string('code', 30)->nullable()->unique();
            $table->string('raison_sociale', 200);
            $table->string('nom_contact', 150)->nullable();

            $table->string('telephone', 30)->nullable();
            $table->string('telephone_secondaire', 30)->nullable();
            $table->string('email', 150)->nullable();
            $table->string('adresse', 255)->nullable();
            $table->string('numero_ninea', 50)->nullable();
            $table->string('numero_registre_commerce', 80)->nullable();

            $table->text('observations')->nullable();
            $table->boolean('actif')->default(true);

            $table->unsignedInteger('user_create_id')->nullable();
            $table->unsignedInteger('user_update_id')->nullable();

            $table->timestamps();
            $table->softDeletes();

            $table->foreign('user_create_id')
                ->references('id')->on('users')
                ->nullOnDelete();

            $table->foreign('user_update_id')
                ->references('id')->on('users')
                ->nullOnDelete();

            $table->index('raison_sociale');
            $table->index('actif');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('support_fournisseurs_stocks');
    }
};
