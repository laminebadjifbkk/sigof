<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('parc_vehicules', function (Blueprint $table) {
            $table->string('etat', 50)->default('operationnel')->change();
        });
    }

    public function down(): void
    {
        Schema::table('parc_vehicules', function (Blueprint $table) {
            $table->enum('etat', ['operationnel','maintenance','hors_service'])
                  ->default('operationnel')
                  ->change();
        });
    }
};

