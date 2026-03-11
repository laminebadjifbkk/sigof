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
        Schema::create('alertes_activites', function (Blueprint $table) {

            $table->id();

            $table->unsignedBigInteger('activitequotidienne_id');

            $table->foreign('activitequotidienne_id')
                ->references('id')
                ->on('activitequotidiennes')
                ->cascadeOnDelete();

            $table->boolean('envoye')->default(false);

            $table->timestamp('date_alerte');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('alertes_activites');
    }
};
