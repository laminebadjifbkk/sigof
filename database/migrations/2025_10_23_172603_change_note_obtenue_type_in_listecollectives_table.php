<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('listecollectives', function (Blueprint $table) {
            // On convertit le champ en string (texte)
            $table->string('note_obtenue')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('listecollectives', function (Blueprint $table) {
            // En cas de rollback, on le remet en double
            $table->double('note_obtenue')->nullable()->change();
        });
    }
};
