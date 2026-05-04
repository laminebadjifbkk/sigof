<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('suivi_post_individuels', function (Blueprint $table) {

            $table->text('raison_marche')
                ->nullable()
                ->after('formation_marche');

            $table->text('raison_diplome')
                ->nullable()
                ->after('diplome_retire');

        });
    }

    public function down(): void
    {
        Schema::table('suivi_post_individuels', function (Blueprint $table) {

            $table->dropColumn('raison_marche');
            $table->dropColumn('raison_diplome');

        });
    }
};
