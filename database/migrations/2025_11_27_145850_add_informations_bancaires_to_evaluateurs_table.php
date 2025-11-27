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
         Schema::table('evaluateurs', function (Blueprint $table) {
            $table->string('banque')->nullable()->after('email');          // Nom de la banque
            $table->string('numero_compte')->nullable()->after('banque'); // Numéro de compte
            $table->string('rib')->nullable()->after('numero_compte');    // RIB complet
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
         Schema::table('evaluateurs', function (Blueprint $table) {
            $table->dropColumn(['banque', 'numero_compte', 'rib']);
        });
    }
};
