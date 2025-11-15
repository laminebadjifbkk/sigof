<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('commissionagrement_operateurs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('commissionagrement_id')
                ->constrained('commissionagrements')
                ->onDelete('cascade')
                ->name('fk_commissionagrement_operateur_commissionagrement');

            $table->foreignId('operateur_id')
                ->constrained('operateurs')
                ->onDelete('cascade')
                ->name('fk_commissionagrement_operateur_operateur');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('commissioncommissionagrement_operateurs');
    }
};
