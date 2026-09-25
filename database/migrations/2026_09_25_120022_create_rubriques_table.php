<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRubriquesTable extends Migration
{
    /**
     * Schema table name to migrate
     * @var string
     */
    public $tableName = 'rubriques';

    /**
     * Référentiel des rubriques de la note de frais.
     * groupe = PEDAGOGIQUE | ADMINISTRATIF, utilisé pour calculer
     * automatiquement Sous total 1 / Sous total 2.
     *
     * @table rubriques
     *
     * @return void
     */
    public function up()
    {
        Schema::create($this->tableName, function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->char('uuid', 36);
            $table->string('libelle', 200);
            $table->enum('groupe', ['PEDAGOGIQUE', 'ADMINISTRATIF']);
            $table->string('unite_defaut', 45)->nullable();
            $table->unsignedInteger('ordre')->default(0);
            $table->boolean('actif')->default(true);
            $table->softDeletes();
            $table->nullableTimestamps();
        });

        // Rubriques standards reprises du modèle de note de frais ONFP
        $now = now();
        \Illuminate\Support\Facades\DB::table($this->tableName)->insert([
            ['uuid' => (string) \Illuminate\Support\Str::uuid(), 'libelle' => "Frais d'intervention", 'groupe' => 'PEDAGOGIQUE', 'unite_defaut' => 'Jours', 'ordre' => 1, 'created_at' => $now, 'updated_at' => $now],
            ['uuid' => (string) \Illuminate\Support\Str::uuid(), 'libelle' => 'Fournitures et supports didactiques', 'groupe' => 'PEDAGOGIQUE', 'unite_defaut' => null, 'ordre' => 2, 'created_at' => $now, 'updated_at' => $now],
            ['uuid' => (string) \Illuminate\Support\Str::uuid(), 'libelle' => 'Matériels pédagogiques', 'groupe' => 'PEDAGOGIQUE', 'unite_defaut' => null, 'ordre' => 3, 'created_at' => $now, 'updated_at' => $now],
            ['uuid' => (string) \Illuminate\Support\Str::uuid(), 'libelle' => "Intrants/Matières d'œuvre", 'groupe' => 'PEDAGOGIQUE', 'unite_defaut' => null, 'ordre' => 4, 'created_at' => $now, 'updated_at' => $now],
            ['uuid' => (string) \Illuminate\Support\Str::uuid(), 'libelle' => 'Transport matériel', 'groupe' => 'ADMINISTRATIF', 'unite_defaut' => null, 'ordre' => 5, 'created_at' => $now, 'updated_at' => $now],
            ['uuid' => (string) \Illuminate\Support\Str::uuid(), 'libelle' => 'Hébergement et restauration', 'groupe' => 'ADMINISTRATIF', 'unite_defaut' => null, 'ordre' => 6, 'created_at' => $now, 'updated_at' => $now],
            ['uuid' => (string) \Illuminate\Support\Str::uuid(), 'libelle' => 'Location salle', 'groupe' => 'ADMINISTRATIF', 'unite_defaut' => null, 'ordre' => 7, 'created_at' => $now, 'updated_at' => $now],
        ]);
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
