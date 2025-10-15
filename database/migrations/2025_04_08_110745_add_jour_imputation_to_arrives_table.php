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
        Schema::table('arrives', function (Blueprint $table) {
                                                                        // Ajouter la colonne 'jour_imputation' (type date ou datetime selon ton besoin)
            $table->date('jour_imputation')->nullable()->after('type'); // ou $table->dateTime('jour_imputation')->nullable(); si tu veux une date + heure
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('arrives', function (Blueprint $table) {
            $table->dropColumn('jour_imputation');
        });
    }
};
