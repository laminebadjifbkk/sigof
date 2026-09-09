<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('onfp_activite_notifications', function (Blueprint $table) {
            $table->id();

            /* $table->foreignId('activite_id')
                ->nullable()
                ->constrained('onfp_activites')
                ->restrictOnDelete();

            $table->foreignId('employee_id')
                ->constrained('employees')
                ->restrictOnDelete(); */
            $table->foreignId('activite_id')
                ->nullable()
                ->constrained('onfp_activites')
                ->restrictOnDelete();

            $table->unsignedInteger('employee_id');

            $table->foreign('employee_id')
                ->references('id')
                ->on('employees')
                ->restrictOnDelete();

            $table->string('type', 100);

            $table->string('titre', 255);
            $table->text('message');

            $table->string('priorite', 30)->default('normale');

            $table->timestamp('lu_at')->nullable();
            $table->timestamp('envoye_at')->nullable();

            $table->timestamps();

            $table->index(['employee_id', 'lu_at']);
            $table->index(['activite_id', 'type']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('onfp_activite_notifications');
    }
};
