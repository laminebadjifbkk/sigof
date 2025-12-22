<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('parc_depenses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('mission_id')->constrained('parc_missions')->cascadeOnDelete();
            $table->enum('type', ['peage', 'hebergement', 'restauration', 'divers']);
            $table->decimal('montant', 12, 2);
            $table->string('note')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('parc_depenses');
    }
};
