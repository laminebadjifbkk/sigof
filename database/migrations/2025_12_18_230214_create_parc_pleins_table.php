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
        Schema::create('parc_pleins', function (Blueprint $table) {
            $table->id();
            $table->foreignId('vehicule_id')->constrained('parc_vehicules')->cascadeOnDelete();
            $table->date('date');
            $table->decimal('quantite_l', 10, 2);
            $table->decimal('prix_unitaire', 10, 2);
            $table->decimal('montant', 12, 2);
            $table->unsignedInteger('kilometrage')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('parc_pleins');
    }
};
