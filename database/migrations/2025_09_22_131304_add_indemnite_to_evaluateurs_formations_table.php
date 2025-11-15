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
            $table->decimal('indemnite', 10, 2)->nullable()->after('date_lettre');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('evaluateurs_formations', function (Blueprint $table) {
            $table->dropColumn('indemnite');
        });
    }
};
