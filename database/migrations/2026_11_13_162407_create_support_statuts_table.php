<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('support_statuts', function (Blueprint $table) {
            $table->id();

            $table->string('code', 30)->unique();
            $table->string('libelle', 100);
            $table->text('description')->nullable();

            $table->boolean('est_terminal')->default(false);
            $table->boolean('actif')->default(true);
            $table->unsignedInteger('ordre')->default(0);

            $table->unsignedInteger('user_create_id')->nullable();
            $table->unsignedInteger('user_update_id')->nullable();

            $table->timestamps();
            $table->softDeletes();

            $table->foreign('user_create_id')
                ->references('id')
                ->on('users')
                ->nullOnDelete();

            $table->foreign('user_update_id')
                ->references('id')
                ->on('users')
                ->nullOnDelete();

            $table->index(
                ['actif', 'ordre'],
                'support_statuts_actif_ordre_index'
            );
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('support_statuts');
    }
};
