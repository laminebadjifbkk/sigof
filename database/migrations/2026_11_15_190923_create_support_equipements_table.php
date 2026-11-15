<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('support_equipements', function (Blueprint $table) {
            $table->id();

            $table->string('reference', 30)->unique();
            $table->string('designation', 200);

            $table->foreignId('support_categorie_equipement_id')
                ->constrained('support_categories_equipements')
                ->restrictOnDelete();

            $table->string('marque', 100)->nullable();
            $table->string('modele', 150)->nullable();
            $table->string('numero_serie', 150)->nullable()->unique();

            $table->date('date_acquisition')->nullable();
            $table->string('fournisseur', 200)->nullable();
            $table->decimal('prix_acquisition', 15, 2)->nullable();
            $table->date('date_fin_garantie')->nullable();

            $table->string('etat_physique', 30)->default('BON');
            $table->string('statut', 30)->default('EN_STOCK');

            $table->string('emplacement', 200)->nullable();
            $table->text('description')->nullable();
            $table->text('observations')->nullable();

            $table->unsignedInteger('user_create_id')->nullable();
            $table->unsignedInteger('user_update_id')->nullable();

            $table->timestamps();
            $table->softDeletes();

            $table->foreign('user_create_id')
                ->references('id')->on('users')
                ->nullOnDelete();

            $table->foreign('user_update_id')
                ->references('id')->on('users')
                ->nullOnDelete();

            $table->index(['statut', 'etat_physique']);
            $table->index(['support_categorie_equipement_id', 'statut']);
            $table->index('date_acquisition');
        });
        DB::statement("
            ALTER TABLE support_equipements
            ADD CONSTRAINT chk_equipement_etat_physique
            CHECK (etat_physique IN ('BON', 'MOYEN', 'MAUVAIS', 'HORS_SERVICE'))
        ");

        DB::statement("
            ALTER TABLE support_equipements
            ADD CONSTRAINT chk_equipement_statut
            CHECK (statut IN ('EN_STOCK', 'AFFECTE', 'EN_MAINTENANCE', 'REFORME', 'PERDU'))
        ");

        DB::statement("
            ALTER TABLE support_equipements
            ADD CONSTRAINT chk_equipement_dates
            CHECK (
                date_acquisition IS NULL
                OR date_fin_garantie IS NULL
                OR date_fin_garantie >= date_acquisition
            )
        ");
    }

    public function down(): void
    {
        Schema::dropIfExists('support_equipements');
    }
};
