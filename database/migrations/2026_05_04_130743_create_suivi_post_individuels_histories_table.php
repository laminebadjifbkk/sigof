<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('suivi_post_individuels_histories', function (Blueprint $table) {

            $table->id();

            $table->foreignId('suivi_post_individuel_id');

            $table->foreign('suivi_post_individuel_id', 'fk_suivi_history')
                ->references('id')
                ->on('suivi_post_individuels')
                ->onDelete('cascade');

            $table->string('action');

            $table->json('old_values')->nullable();
            $table->json('new_values')->nullable();

            $table->foreignId('user_id')->nullable();

            $table->text('note')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('suivi_post_individuels_histories');
    }
};
