<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('onfp_activite_tags', function (Blueprint $table) {
            $table->id();

            $table->string('nom', 100)->unique();
            $table->string('slug', 120)->unique();

            $table->string('couleur', 20)->nullable();

            $table->text('description')->nullable();

            $table->boolean('actif')->default(true);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('onfp_activite_tags');
    }
};
