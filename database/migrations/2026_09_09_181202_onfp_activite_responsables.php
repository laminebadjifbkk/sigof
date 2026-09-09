<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('onfp_activite_responsables', function (Blueprint $table) {
            $table->id();

            /*   $table->foreignId('activite_id')
                ->constrained('onfp_activites')
                ->restrictOnDelete();

            $table->foreignId('employee_id')
                ->constrained('employees')
                ->restrictOnDelete(); */
            // onfp_activites.id = BIGINT UNSIGNED
            $table->foreignId('activite_id')
                ->constrained('onfp_activites')
                ->restrictOnDelete();

            // employees.id = INT UNSIGNED
            $table->unsignedInteger('employee_id');

            $table->foreign('employee_id')
                ->references('id')
                ->on('employees')
                ->restrictOnDelete();

            $table->string('role', 50)->default('responsable');

            $table->boolean('is_principal')->default(false);

            $table->timestamps();

            $table->unique(['activite_id', 'employee_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('onfp_activite_responsables');
    }
};
