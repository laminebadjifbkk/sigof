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
        Schema::table('notifications_region', function (Blueprint $table) {
            $table->dropUnique('notifications_region_region_unique'); // nom exact selon l'erreur
            $table->unique(['region', 'modules_id'], 'notifications_region_region_module_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('notifications_region', function (Blueprint $table) {
            $table->dropUnique('notifications_region_region_module_unique');
            $table->unique('region', 'notifications_region_region_unique');
        });
    }
};
