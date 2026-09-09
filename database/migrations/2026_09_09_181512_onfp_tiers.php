<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('onfp_tiers', function (Blueprint $table) {
            $table->id();

            $table->string('type', 50)->nullable();

            $table->string('nom', 255);
            $table->string('organisation', 255)->nullable();

            $table->string('fonction', 150)->nullable();

            $table->string('telephone', 50)->nullable();
            $table->string('email', 150)->nullable();

            $table->text('adresse')->nullable();
            $table->text('observation')->nullable();

            $table->boolean('actif')->default(true);

            $table->timestamps();
            $table->softDeletes();

            $table->index(['type', 'actif']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('onfp_tiers');
    }
};
