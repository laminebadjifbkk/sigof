<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('parc_employee_mission', function (Blueprint $table) {
            $table->id();

            // mission_id doit être BIGINT UNSIGNED
            $table->unsignedBigInteger('mission_id');
            $table->foreign('mission_id')->references('id')->on('parc_missions')->cascadeOnDelete();

            // employee_id doit être INT UNSIGNED (car employees.id est INT)
            $table->unsignedInteger('employee_id');
            $table->foreign('employee_id')->references('id')->on('employees')->cascadeOnDelete();

            $table->string('role')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('parc_employee_mission');
    }
};
