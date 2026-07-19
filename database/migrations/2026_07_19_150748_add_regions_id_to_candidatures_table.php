<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('candidatures', function (Blueprint $table) {
            $table->unsignedInteger('regions_id')
                ->nullable()
                ->after('users_id');

            $table->foreign('regions_id')
                ->references('id')
                ->on('regions')
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('candidatures', function (Blueprint $table) {
            $table->dropForeign(['regions_id']);
            $table->dropColumn('regions_id');
        });
    }
};
