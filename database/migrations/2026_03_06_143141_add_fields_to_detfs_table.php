<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('detfs', function (Blueprint $table) {
            $table->string('lieu_de_formation')->nullable()->after('etat');
            $table->string('pvchoixoperateur')->nullable()->after('lieu_de_formation');
            $table->string('periode_de_formation')->nullable()->after('pvchoixoperateur');
        });
    }

    public function down(): void
    {
        Schema::table('detfs', function (Blueprint $table) {
            $table->dropColumn(['lieu_de_formation', 'pvchoixoperateur', 'periode_de_formation']);
        });
    }
};