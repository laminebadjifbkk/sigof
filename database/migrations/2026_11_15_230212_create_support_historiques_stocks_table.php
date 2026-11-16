<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('support_historiques_stocks', function (Blueprint $table) {
            $table->id();

            $table->unsignedInteger('user_id')->nullable();

            $table->string('entite', 80);
            $table->unsignedBigInteger('entite_id');

            $table->string('action', 50);
            $table->text('description')->nullable();

            $table->json('anciennes_valeurs')->nullable();
            $table->json('nouvelles_valeurs')->nullable();

            $table->string('adresse_ip', 45)->nullable();
            $table->text('user_agent')->nullable();

            $table->timestamp('created_at')->useCurrent();

            $table->foreign('user_id', 'fk_shs_user')
    ->references('id')->on('users')
    ->nullOnDelete();

            $table->index(['entite', 'entite_id'], 'shs_entite_idx');
$table->index(['user_id', 'created_at'], 'shs_user_date_idx');
$table->index(['action', 'created_at'], 'shs_action_date_idx');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('support_historiques_stocks');
    }
};
