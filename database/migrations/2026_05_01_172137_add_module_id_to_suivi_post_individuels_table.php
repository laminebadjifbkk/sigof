<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('suivi_post_individuels', function (Blueprint $table) {
            $table->unsignedBigInteger('module_id')->nullable()->after('individuelles_id');
            $table->string('statut')->nullable()->after('module_id');
        });
    }

    public function down(): void
    {
        Schema::table('suivi_post_individuels', function (Blueprint $table) {
            $table->dropColumn(['module_id', 'module_id']);
        });
    }
};
