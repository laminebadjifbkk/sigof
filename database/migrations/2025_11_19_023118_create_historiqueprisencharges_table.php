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
        Schema::create('historiqueprisencharges', function (Blueprint $table) {

            $table->id();

            $table->unsignedBigInteger('formulaire_id'); // OK si formulaires.id = bigint
            $table->unsignedInteger('user_id');          // correspond à users.id = int

            $table->string('statut');
            $table->text('motif')->nullable();
            $table->timestamps();

            // Foreign keys
            $table->foreign('formulaire_id')
                ->references('id')->on('formulaires')
                ->onDelete('cascade');

            $table->foreign('user_id')
                ->references('id')->on('users')
                ->onDelete('cascade');
        });
    }
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('historiqueprisencharges');
    }
};
