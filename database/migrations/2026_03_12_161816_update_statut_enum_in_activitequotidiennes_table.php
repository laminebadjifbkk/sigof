<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("
            ALTER TABLE activitequotidiennes 
            MODIFY statut ENUM(
                'en_attente',
                'en_cours',
                'terminee',
                'validee',
                'rejete',
                'retard'
            ) DEFAULT 'en_attente'
        ");
    }

    public function down(): void
    {
        DB::statement("
            ALTER TABLE activitequotidiennes 
            MODIFY statut ENUM(
                'en_attente',
                'en_cours',
                'terminee',
                'validee',
                'rejete'
            ) DEFAULT 'en_attente'
        ");
    }
};