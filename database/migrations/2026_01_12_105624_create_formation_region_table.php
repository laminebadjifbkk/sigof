<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFormationRegionTable extends Migration
{
    public function up()
    {
        Schema::create('formation_region', function (Blueprint $table) {
            $table->id();

            $table->unsignedInteger('formation_id');
            $table->unsignedInteger('region_id');

            $table->timestamps();

            $table->unique(['formation_id', 'region_id']);

            $table->foreign('formation_id')
                ->references('id')->on('formations')
                ->onDelete('cascade');

            $table->foreign('region_id')
                ->references('id')->on('regions')
                ->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('formation_region');
    }
}
