<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('onfp_activite_suiveurs', function (Blueprint $table) {
            $table->id();

            /* $table->foreignId('activite_id')
                ->constrained('onfp_activites')
                ->restrictOnDelete();

            $table->foreignId('employee_id')
                ->constrained('employees')
                ->restrictOnDelete(); */
            $table->foreignId('activite_id')
                ->constrained('onfp_activites')
                ->restrictOnDelete();

            $table->unsignedInteger('employee_id');

            $table->foreign('employee_id')
                ->references('id')
                ->on('employees')
                ->restrictOnDelete();

            $table->timestamps();

            $table->unique(['activite_id', 'employee_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('onfp_activite_suiveurs');
    }
};
