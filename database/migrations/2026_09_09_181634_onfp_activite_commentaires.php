<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('onfp_activite_commentaires', function (Blueprint $table) {
            $table->id();

            /*  $table->foreignId('activite_id')
                ->constrained('onfp_activites')
                ->restrictOnDelete();

            $table->foreignId('employee_id')
                ->nullable()
                ->constrained('employees')
                ->restrictOnDelete(); */
            $table->foreignId('activite_id')
                ->constrained('onfp_activites')
                ->restrictOnDelete();

            $table->unsignedInteger('employee_id')->nullable();

            $table->foreign('employee_id')
                ->references('id')
                ->on('employees')
                ->restrictOnDelete();

            $table->text('commentaire');

            $table->boolean('interne')->default(true);

            $table->timestamps();
            $table->softDeletes();

            $table->index(['activite_id', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('onfp_activite_commentaires');
    }
};
