<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddScanCvToEvaluateursTable extends Migration
{
    public function up(): void
    {
        Schema::table('evaluateurs', function (Blueprint $table) {
            $table->string('scan_cv')->nullable()->after('email'); // ajuste 'email' selon la colonne précédente réelle
        });
    }

    public function down(): void
    {
        Schema::table('evaluateurs', function (Blueprint $table) {
            $table->dropColumn('scan_cv');
        });
    }
}
