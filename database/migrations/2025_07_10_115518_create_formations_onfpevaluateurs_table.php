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
        Schema::create('formations_onfpevaluateurs', function (Blueprint $table) {

            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->unsignedInteger('formations_id');
            $table->unsignedInteger('onfpevaluateurs_id');

            $table->index(["onfpevaluateurs_id"], 'fk_formations_onfpevaluateurs_onfpevaluateurs1_idx');

            $table->index(["formations_id"], 'fk_formations_onfpevaluateurs_formations1_idx');
            $table->softDeletes();
            $table->nullableTimestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('formations_onfpevaluateurs');
    }
};
