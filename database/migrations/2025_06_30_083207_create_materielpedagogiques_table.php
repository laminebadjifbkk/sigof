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
        Schema::create('materielpedagogiques', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->id();
            $table->uuid('uuid');
            $table->boolean('acquis')->default(false);
            $table->boolean('non_acquis')->default(false);
            $table->text('observations')->nullable();
            $table->longText('nb')->nullable();
            $table->unsignedInteger('formations_id');
            $table->unsignedInteger('feuillepresences_id')->nullable();
            $table->unsignedInteger('feuillepresencecollectives_id')->nullable();
            $table->softDeletes();
            $table->nullableTimestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('materielpedagogiques');
    }
};
