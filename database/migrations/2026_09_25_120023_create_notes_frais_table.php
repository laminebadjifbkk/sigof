<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNotesFraisTable extends Migration
{
    /**
     * Schema table name to migrate
     * @var string
     */
    public $tableName = 'notes_frais';

    /**
     * Une note de frais (acompte ou définitive) rattachée à une formation.
     * On remonte à l'opérateur via formations.operateurs_id, on ne duplique
     * donc pas operateurs_id ici : formations est déjà la table pivot.
     *
     * @table notes_frais
     *
     * @return void
     */
    public function up()
    {
        Schema::create($this->tableName, function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->char('uuid', 36);
            $table->enum('type', ['ACOMPTE', 'DEFINITIVE']);
            $table->enum('statut', [
                'BROUILLON',
                'SOUMISE',
                'VALIDEE_DIOF',
                'VALIDEE_DF',
                'PAYEE',
                'REJETEE',
            ])->default('BROUILLON');

            // Champs repris tels quels du document (session, période, lieu, bénéficiaires)
            $table->string('session_label', 200)->nullable(); // "Session 1", "Session 2"...
            $table->dateTime('periode_debut')->nullable();
            $table->dateTime('periode_fin')->nullable();
            $table->string('lieu', 200)->nullable();
            $table->string('beneficiaires', 200)->nullable();
            $table->string('banque_rib', 200)->nullable();

            // Montants calculés côté backend, jamais saisis directement
            $table->decimal('sous_total_pedagogique', 15, 2)->default(0);
            $table->decimal('sous_total_administratif', 15, 2)->default(0);
            $table->decimal('total_frais_operateur', 15, 2)->default(0); // (A)
            $table->decimal('taux_acompte', 5, 2)->default(30.00); // paramétrable par convention
            $table->decimal('montant_acompte_recu', 15, 2)->default(0); // (B), repris de la note ACOMPTE liée
            $table->decimal('reliquat', 15, 2)->default(0); // (A - B) ou 100% du total

            $table->unsignedInteger('formations_id');
            // Auto-référence : la note DEFINITIVE pointe vers la note ACOMPTE dont elle reprend (B)
            $table->unsignedInteger('note_acompte_id')->nullable();

            $table->unsignedInteger('valide_par_id')->nullable(); // users.id
            $table->timestamp('date_validation')->nullable();

            $table->index(['formations_id'], 'fk_notesfrais_formations1_idx');
            $table->index(['note_acompte_id'], 'fk_notesfrais_notesfrais1_idx');
            $table->index(['valide_par_id'], 'fk_notesfrais_users1_idx');

            $table->softDeletes();
            $table->nullableTimestamps();

            $table->foreign('formations_id', 'fk_notesfrais_formations1_idx')
                ->references('id')->on('formations')
                ->onDelete('no action')
                ->onUpdate('no action');

            $table->foreign('note_acompte_id', 'fk_notesfrais_notesfrais1_idx')
                ->references('id')->on($this->tableName)
                ->onDelete('no action')
                ->onUpdate('no action');

            $table->foreign('valide_par_id', 'fk_notesfrais_users1_idx')
                ->references('id')->on('users')
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
