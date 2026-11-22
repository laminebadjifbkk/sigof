<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('antenne_employee', function (Blueprint $table) {
            $table->engine = 'InnoDB';

            $table->increments('id');

            $table->unsignedInteger('antenne_id');
            $table->unsignedInteger('employee_id');

            $table->timestamps();

            $table->unique(
                ['antenne_id', 'employee_id'],
                'antenne_employee_unique'
            );

            $table->index('employee_id', 'antenne_employee_employee_idx');

            $table->foreign('antenne_id', 'antenne_employee_antenne_fk')
                ->references('id')
                ->on('antennes')
                ->cascadeOnDelete();

            $table->foreign('employee_id', 'antenne_employee_employee_fk')
                ->references('id')
                ->on('employees')
                ->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('antenne_employee');
    }
};
