<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDatesToCommissionagrementsTable extends Migration
{
    public function up(): void
    {
        Schema::table('commissionagrements', function (Blueprint $table) {
            $table->date('date_ouverture')->nullable()->after('description');
            $table->date('date_fermeture')->nullable()->after('date_ouverture');
            $table->dateTime('debut_commission')->nullable()->after('date_fermeture');
            $table->dateTime('fin_commission')->nullable()->after('debut_commission');
        });
    }

    public function down(): void
    {
        Schema::table('commissionagrements', function (Blueprint $table) {
            $table->dropColumn([
                'date_ouverture',
                'date_fermeture',
                'debut_commission',
                'fin_commission',
            ]);
        });
    }
}
