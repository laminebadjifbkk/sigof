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
        Schema::table('evaluateurs_formations', function (Blueprint $table) {
            $table->string('numero_lettre')->nullable()->after('formations_id');
            $table->date('date_lettre')->nullable()->after('numero_lettre');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('evaluateurs_formations', function (Blueprint $table) {
            $table->dropColumn(['numero_lettre', 'date_lettre']);
        });
    }
};
