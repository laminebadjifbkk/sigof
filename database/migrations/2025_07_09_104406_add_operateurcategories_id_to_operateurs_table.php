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
        Schema::table('operateurs', function (Blueprint $table) {
            $table->foreignId('operateurcategories_id')->nullable()->constrained('operateurcategories')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('operateurs', function (Blueprint $table) {
            $table->dropForeign(['operateurcategories_id']);
            $table->dropColumn('operateurcategories_id');
        });
    }
};
