<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('collectivemodules', function (Blueprint $table) {
            $table->unsignedBigInteger('ingenieurs_id')
                  ->nullable()
                  ->after('regions_id');
        });
    }

    public function down(): void
    {
        Schema::table('collectivemodules', function (Blueprint $table) {
            $table->dropForeign(['ingenieurs_id']);
            $table->dropColumn('ingenieurs_id');
        });
    }
};
