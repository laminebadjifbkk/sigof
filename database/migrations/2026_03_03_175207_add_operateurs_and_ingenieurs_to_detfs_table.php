<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddOperateursAndIngenieursToDetfsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('detfs', function (Blueprint $table) {
            $table->unsignedBigInteger('operateurs_id')->nullable()->after('date1');
            $table->unsignedBigInteger('ingenieurs_id')->nullable()->after('operateurs_id');
            $table->decimal('montant_prevu', 15, 2)->nullable();
            $table->decimal('montant_realise', 15, 2)->nullable();
            $table->string('etat', 50)->nullable();
            $table->text('description')->nullable();

            // Si tu veux des clés étrangères (optionnel)
            // $table->foreign('operateurs_id')->references('id')->on('operateurs')->onDelete('set null');
            // $table->foreign('ingenieurs_id')->references('id')->on('ingenieurs')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('detfs', function (Blueprint $table) {
            $table->dropColumn(['operateurs_id', 'ingenieurs_id', 'montant_prevu', 'montant_realise', 'etat', 'description']);
        });
    }
}
