<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('onfp_activite_tiers', function (Blueprint $table) {
            $table->id();

            $table->foreignId('activite_id')
                ->constrained('onfp_activites')
                ->restrictOnDelete();

            $table->foreignId('tier_id')
                ->constrained('onfp_tiers')
                ->restrictOnDelete();

            $table->string('role', 150)->nullable();

            $table->text('observation')->nullable();

            $table->timestamps();

            $table->unique(['activite_id', 'tier_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('onfp_activite_tiers');
    }
};
