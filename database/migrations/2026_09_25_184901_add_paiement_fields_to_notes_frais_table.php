<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddPaiementFieldsToNotesFraisTable extends Migration
{
    public $tableName = 'notes_frais';

    /**
     * Sépare la traçabilité du paiement de celle de la validation.
     * `statut` reste PAYEE pour l'affichage rapide, mais ces champs
     * répondent à "payé quand, comment, par qui, avec quelle référence".
     *
     * @return void
     */
    public function up()
    {
        Schema::table($this->tableName, function (Blueprint $table) {
            $table->string('mode_paiement', 45)->nullable()->after('date_validation'); // virement, chèque, espèces...
            $table->string('reference_paiement', 200)->nullable()->after('mode_paiement'); // n° de virement/chèque
            $table->timestamp('date_paiement')->nullable()->after('reference_paiement');
            $table->unsignedInteger('paye_par_id')->nullable()->after('date_paiement'); // users.id

            $table->index(['paye_par_id'], 'fk_notesfrais_users2_idx');

            $table->foreign('paye_par_id', 'fk_notesfrais_users2_idx')
                ->references('id')->on('users')
                ->onDelete('no action')
                ->onUpdate('no action');
        });
    }

    public function down()
    {
        Schema::table($this->tableName, function (Blueprint $table) {
            $table->dropForeign('fk_notesfrais_users2_idx');
            $table->dropColumn(['mode_paiement', 'reference_paiement', 'date_paiement', 'paye_par_id']);
        });
    }
}
