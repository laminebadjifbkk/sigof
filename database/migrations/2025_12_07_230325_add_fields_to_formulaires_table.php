<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFieldsToFormulairesTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('formulaires', function (Blueprint $table) {
            $table->string('certificat_file')->nullable();
            $table->string('responsable_etablieement')->nullable();
            $table->string('adresse_etablessement')->nullable();
            $table->string('telephone_etablissement')->nullable();
            $table->string('annee_scolaire')->nullable();
            $table->decimal('montant_onfp', 12, 2)->nullable();
            $table->string('statut_certificat')->nullable();
            $table->unsignedBigInteger('users_id')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('update_by')->nullable();
            $table->string('autre_1')->nullable();
            $table->string('autre_2')->nullable();
            // Nouveaux champs demandés
            $table->text('description')->nullable();
            $table->text('commentaire')->nullable();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('formulaires', function (Blueprint $table) {
            $table->dropColumn([
                'certificat_file',
                'responsable_etablieement',
                'adresse_etablessement',
                'telephone_etablissement',
                'annee_scolaire',
                'montant_onfp',
                'statut_certificat',
                'users_id',
                'created_by',
                'update_by',
                'autre_1',
                'autre_2',
                'description',
                'commentaire',
            ]);
        });
    }
}
