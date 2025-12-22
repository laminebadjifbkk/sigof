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
        Schema::table('parc_vehicules', function (Blueprint $table) {
            $table->unsignedBigInteger('chauffeur_id')->nullable()->after('id');
            $table->foreign('chauffeur_id')
                ->references('id')
                ->on('parc_chauffeurs')
                ->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::table('parc_vehicules', function (Blueprint $table) {
            $table->dropForeign(['chauffeur_id']);
            $table->dropColumn('chauffeur_id');
        });
    }
};
