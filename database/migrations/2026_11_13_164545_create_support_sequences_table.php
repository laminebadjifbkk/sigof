<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('support_sequences', function (Blueprint $table) {
            $table->id();

            $table->string('code', 50);
            $table->unsignedInteger('annee');
            $table->unsignedBigInteger('dernier_numero')->default(0);

            $table->timestamps();

            $table->unique(
                ['code', 'annee'],
                'support_sequences_code_annee_unique'
            );
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('support_sequences');
    }
};
