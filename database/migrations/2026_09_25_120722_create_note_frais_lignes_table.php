<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNoteFraisLignesTable extends Migration
{
    /**
     * Schema table name to migrate
     * @var string
     */
    public $tableName = 'note_frais_lignes';

    /**
     * Chaque ligne du tableau Rubriques/Libellés/Unités/Qte/PU/Montant.
     *
     * @table note_frais_lignes
     *
     * @return void
     */
    public function up()
    {
        Schema::create($this->tableName, function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->char('uuid', 36);
            $table->string('unite', 45)->nullable();
            $table->decimal('qte', 12, 2)->default(0);
            $table->decimal('pu', 15, 2)->default(0);
            $table->decimal('montant', 15, 2)->default(0); // = qte * pu, recalculé en base

            $table->unsignedInteger('notes_frais_id');
            $table->unsignedInteger('rubriques_id');

            $table->index(['notes_frais_id'], 'fk_notefraislignes_notesfrais1_idx');
            $table->index(['rubriques_id'], 'fk_notefraislignes_rubriques1_idx');

            $table->softDeletes();
            $table->nullableTimestamps();

            $table->foreign('notes_frais_id', 'fk_notefraislignes_notesfrais1_idx')
                ->references('id')->on('notes_frais')
                ->onDelete('cascade')
                ->onUpdate('no action');

            $table->foreign('rubriques_id', 'fk_notefraislignes_rubriques1_idx')
                ->references('id')->on('rubriques')
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
