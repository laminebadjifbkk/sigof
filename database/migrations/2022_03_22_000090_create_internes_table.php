<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInternesTable extends Migration
{
    /**
     * Schema table name to migrate
     * @var string
     */
    public $tableName = 'internes';

    /**
     * Run the migrations.
     * @table internes
     *
     * @return void
     */
    public function up()
    {
        Schema::create($this->tableName, function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->char('uuid', 36);
            $table->string('numero_interne', 200)->nullable();
            $table->unsignedInteger('courriers_id');

            $table->index(["courriers_id"], 'fk_interne_courriers1_idx');
            $table->softDeletes();
            $table->nullableTimestamps();


            $table->foreign('courriers_id', 'fk_interne_courriers1_idx')
                ->references('id')->on('courriers')
                ->onDelete('no action')
                ->onUpdate('no action');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists($this->tableName);
    }
}
