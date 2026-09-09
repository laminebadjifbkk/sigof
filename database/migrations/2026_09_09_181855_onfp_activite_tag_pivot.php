<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('onfp_activite_tag_pivot', function (Blueprint $table) {
            $table->foreignId('activite_id')
                ->constrained('onfp_activites')
                ->restrictOnDelete();

            $table->foreignId('tag_id')
                ->constrained('onfp_activite_tags')
                ->restrictOnDelete();

            $table->timestamps();

            $table->primary(['activite_id', 'tag_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('onfp_activite_tag_pivot');
    }
};
