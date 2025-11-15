<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('validationoperateurformateurs', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->id();
            $table->char('uuid', 36);
            $table->unsignedInteger('validated_id');
            $table->string('action')->nullable();
            $table->string('session')->nullable();
            $table->longText('motif')->nullable();
            $table->unsignedInteger('operateurformateurs_id')->nullable();
            $table->softDeletes();
            $table->nullableTimestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('validationoperateurformateurs');
    }
};
