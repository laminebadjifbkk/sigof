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
        Schema::create('parc_maintenances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('vehicule_id')->constrained('parc_vehicules')->cascadeOnDelete();
            $table->enum('type', ['vidange', 'pneus', 'freins', 'revision', 'reparation', 'autre']);
            $table->date('date');
            $table->unsignedInteger('kilometrage')->nullable();
            $table->decimal('montant', 12, 2)->default(0);
            $table->string('fournisseur')->nullable();
            $table->string('note')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('parc_maintenances');
    }
};
