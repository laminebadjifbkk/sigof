<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('candidatures', function (Blueprint $table) {
            $table->uuid('uuid')->nullable()->after('id');
        });

        // Générer un UUID pour les enregistrements existants
        DB::table('candidatures')->orderBy('id')->get()->each(function ($candidature) {
            DB::table('candidatures')
                ->where('id', $candidature->id)
                ->update([
                    'uuid' => (string) Str::uuid(),
                ]);
        });

        Schema::table('candidatures', function (Blueprint $table) {
            $table->uuid('uuid')->nullable(false)->unique()->change();
        });
    }

    public function down(): void
    {
        Schema::table('candidatures', function (Blueprint $table) {
            $table->dropUnique(['uuid']);
            $table->dropColumn('uuid');
        });
    }
};
