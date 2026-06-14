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
        Schema::table('listecollectives', function (Blueprint $table) {
            $table->string('numero_attestation')->nullable()->unique()->after('attestation');
        });

        // Idem pour individuelles
        Schema::table('individuelles', function (Blueprint $table) {
            $table->string('numero_attestation')->nullable()->unique()->after('attestation');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('listecollectives', function (Blueprint $table) {
            $table->dropColumn('numero_attestation');
        });

        Schema::table('individuelles', function (Blueprint $table) {
            $table->dropColumn('numero_attestation');
        });
    }
};
