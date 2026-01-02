<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('parc_employee_mission', function (Blueprint $table) {
            $table->unsignedBigInteger('vehicule_id')->nullable()->after('role');
            $table->foreign('vehicule_id')->references('id')->on('parc_vehicules')->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('parc_employee_mission', function (Blueprint $table) {
            $table->dropForeign(['vehicule_id']);
            $table->dropColumn('vehicule_id');
        });
    }
};
