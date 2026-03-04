<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('budget_labels', function (Blueprint $table) {
            $table->string('type')->after('libelle');
        });
    }

    public function down(): void
    {
        Schema::table('budget_labels', function (Blueprint $table) {
            $table->dropColumn('type'); // <-- important
        });
    }
};