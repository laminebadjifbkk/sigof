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
        Schema::create('emargements', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->id();
            $table->char('uuid', 36);
            $table->unsignedInteger('formations_id');
            $table->string('jour', 50);
            $table->dateTime('date')->nullable(true);
            $table->longText('observations')->nullable();
            $table->string('file', 200)->nullable();
            $table->unsignedInteger('individuelles_id')->nullable();
            $table->softDeletes();
            $table->nullableTimestamps();

            $table->index(["individuelles_id"], 'fk_emargements_individuelles1_idx');

            $table->foreign('individuelles_id', 'fk_emargements_individuelles1_idx')
                ->references('id')->on('individuelles')
                ->onDelete('no action')
                ->onUpdate('no action');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('emargements');
    }
};
