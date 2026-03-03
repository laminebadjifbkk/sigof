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
        Schema::create('detfs_payments', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->unsignedInteger('detfs_budget_item_id');
            $table->date('date_paiement');
            $table->decimal('montant', 15, 2);
            $table->string('mode_paiement', 50)->nullable();
            $table->string('reference', 100)->nullable();
            $table->timestamps();

            $table->foreign('detfs_budget_item_id')->references('id')->on('detfs_budget_items')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('detfs_payments');
    }
};
