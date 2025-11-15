<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('formulaires', function (Blueprint $table) {
            $table->id();
            $table->string('cin', 14)->unique();
            $table->string('civilite', 5);
            $table->string('prenom');
            $table->string('nom');
            $table->date('date_naissance');
            $table->string('lieu_naissance');
            $table->string('email')->nullable()->unique();
            $table->string('telephone');
            $table->string('telephone_secondaire')->nullable();
            $table->string('adresse');
            $table->string('dernier_diplome')->nullable();
            $table->string('nom_etablissement');
            $table->string('region');
            $table->string('formation');
            $table->string('diplome_vise');
            $table->decimal('montant_inscription', 10, 2);
            $table->decimal('montant_mensualite', 10, 2);
            $table->decimal('montant_unique', 10, 2)->nullable();
            $table->integer('duree');
            $table->string('handicape');
            $table->string('type_handicap')->nullable();
            $table->string('orphelin');
            $table->string('type_orphelin')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('formulaires');
    }
};