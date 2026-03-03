<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('detfs_budget_items', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->unsignedInteger('detfs_id');
            $table->unsignedInteger('budget_label_id');
            $table->string('unite', 50)->nullable();
            $table->integer('quantite')->default(1);
            $table->decimal('prix_unitaire', 15, 2)->nullable();
            $table->decimal('montant', 15, 2)->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->foreign('detfs_id')->references('id')->on('detfs')->onDelete('cascade');
            $table->foreign('budget_label_id')->references('id')->on('budget_labels')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('detfs_budget_items');
    }
};
