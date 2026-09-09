<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('onfp_tache_responsables', function (Blueprint $table) {
            $table->id();

            /* $table->foreignId('tache_id')
                ->constrained('onfp_taches')
                ->restrictOnDelete();

            $table->foreignId('employee_id')
                ->constrained('employees')
                ->restrictOnDelete(); */
            $table->foreignId('tache_id')
                ->constrained('onfp_taches')
                ->restrictOnDelete();

            $table->unsignedInteger('employee_id');

            $table->foreign('employee_id')
                ->references('id')
                ->on('employees')
                ->restrictOnDelete();

            $table->boolean('is_principal')->default(false);

            $table->timestamps();

            $table->unique(['tache_id', 'employee_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('onfp_tache_responsables');
    }
};
