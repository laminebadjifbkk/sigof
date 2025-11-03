<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('formulaires', function (Blueprint $table) {
            $table->string('facture_file')->nullable()->after('type_orphelin');
            $table->string('cin_file')->nullable()->after('facture_file');
        });
    }

    public function down(): void
    {
        Schema::table('formulaires', function (Blueprint $table) {
            $table->dropColumn(['facture_file', 'cin_file']);
        });
    }
};
