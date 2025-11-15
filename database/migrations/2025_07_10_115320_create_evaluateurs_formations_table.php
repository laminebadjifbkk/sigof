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
        Schema::create('evaluateurs_formations', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->unsignedInteger('evaluateurs_id');
            $table->unsignedInteger('formations_id');

            $table->index(["formations_id"], 'fk_evaluateurs_formations_formations1_idx');

            $table->index(["evaluateurs_id"], 'fk_evaluateurs_formations_evaluateurs1_idx');
            $table->softDeletes();
            $table->nullableTimestamps();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('evaluateurs_formations');
    }
};
